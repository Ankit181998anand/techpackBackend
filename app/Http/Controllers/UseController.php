<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProducts;
use App\Models\Products;
use App\Models\Download;
use App\Models\User;
use App\Models\File;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class UseController extends Controller
{
    //

    public function getAllUsers()
    {

        $Users = User::with(['roles:name'])->get();

        return response()->json(['Users' => $Users], 200);

    }


    public function getUserProductList($userId)
    {
        // Fetch the cart items based on the user ID
        $userProducts = UserProducts::where('user_id', $userId)->first(); // Retrieve a single user product record

        if (!$userProducts) {
            return response()->json(['product_details' => []]);
        }

        $productIds = $userProducts->product_ids;
        $arrayOfProducts = explode(',', $productIds);

        $productDetails = [];

        foreach ($arrayOfProducts as $productId) {
            $product = Products::with([
                'images' => function ($query) {
                    $query->where('image_type', 'image1');
                }
            ])
                ->find($productId);

            if ($product) {
                $downloadCount = Download::where('product_id', $productId)->sum('downlode_count');
                $productDetails[] = [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'images' => $product->images,
                    'dowmload_count' => $downloadCount
                ];
            }
        }

        return response()->json([
            'product_details' => $productDetails,
        ]);
    }

    public function downloadFile(Request $request)
    {
        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'productId' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Retrieve the file path based on the product ID
        $file = File::where('product_id', $request->productId)->first();

        if (!$file) {
            return response()->json(['error' => 'File not found for the provided product ID'], 404);
        }

         // Get the authenticated user ID
    $userId = $request->UserId; // Assuming authenticated user

    // Check if a record with the user ID and product ID exists
    $download = Download::where('user_id', $userId)
                          ->where('product_id', $request->productId)
                          ->first();

    if ($download) {
        // Increment the download count if the record exists
        $download->downlode_count++;
        $download->save();
    } else {
        // Create a new record if the record does not exist
        Download::create([
            'user_id' => $userId,
            'product_id' => $request->productId,
            'downlode_count' => 1, // Initial count
        ]);
    }

        // Get AWS S3 credentials and bucket name from .env file
        $awsCredentials = [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ];

        $region = env('AWS_DEFAULT_REGION');
        $bucket = env('AWS_BUCKET');
        Log::info('AWS Credentials', [$awsCredentials]);
        Log::info('AWS region:', [$region]);
        Log::info('AWS bucket:', [$bucket]);
        // Initialize the S3 client
        $s3 = new S3Client([
            'region' => $region,
            'version' => 'latest',
            'credentials' => $awsCredentials,
        ]);

        $basePath = 'https://techpack-files.s3.ap-south-1.amazonaws.com/';
       $filePath = str_replace($basePath, '', $file->file_path);
        // Generate a pre-signed URL for the file with a 24-hour expiration time
        $command = $s3->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $filePath,
        ]);

        $url = $s3->createPresignedRequest($command, '+24 hours')->getUri();

        // Return the URL in the JSON response
        return response()->json(['download_url' => (string) $url]);
    }
}
