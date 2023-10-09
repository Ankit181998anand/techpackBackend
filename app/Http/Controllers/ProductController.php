<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductsRequest;
use App\Http\Requests\UploadRequest;
use App\Models\Products;
use App\Models\Uplodes;

class ProductController extends Controller
{
    //

    public function test(Request $request){
        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();

        try {
            $path = $file->storeAs('images', $fileName, 's3');
        } catch (\Exception $e) {
            \Log::error('S3 Upload Error: ' . $e->getMessage());
            return response()->json(['error' => 'File upload failed.'], 500);
        }

        return response()->json(['path' => 'https://techpack-frontend-test.s3.ap-south-1.amazonaws.com/'.$path], 200);

    }

    public function store(ProductsRequest $request){



        $products = Products::create([
            'product_sku' => $request->productSku,
            'product_slug'=> $request->productSlug,
            'product_name'=> $request->productName,
            'meta_desc'=> $request->metaDesc,
            'meta_keyword'=> $request->metaKeyword,
            'short_description'=> $request->shortDescription,
            'long_description'=> $request->longDescription,
            'addi_info'=> $request->addiInfo,
            'product-price'=> $request->productPrice,
            'cat_id'=> $request->catId,
            'isActive'=>'1'
        ]);

        return response()->json(['message' => 'products created successfully', 'products' => $products], 201);
    }

    public function uploadFiles(Request $request)
    {
        $s3Paths = [];

        // Define the files and their respective names
        $files = [
            'image1' => 'image1',
            'image2' => 'image2',
            'image3' => 'image3',
            'zipFile' => 'zipFile'
        ];
        $count=0;
        try {
            foreach ($files as $requestFile => $fileName) {
                if ($request->hasFile($requestFile)) {
                    $count++;
                    $file = $request->file($requestFile);
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('images', $fileName, 's3');
                    $s3Paths[] = 'https://techpack-frontend-test.s3.ap-south-1.amazonaws.com/'.$path;
                }
            }
        } catch (\Exception $e) {
            \Log::error('S3 Upload Error: ' . $e->getMessage());
            return response()->json(['error' => 'File upload failed.'], 500);
        }

        try{
            foreach($s3Paths as $data){

                Uplodes::create([
                    'path'=>$data,
                    'product_id'=>$request->productId,
                    'isActive'=>"1"
                ]);

            }
        
        } 
        catch(\Exception $e){
            \Log::error('Database Save Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save to the database.'], 500);
        }

        return response()->json(['paths' => $s3Paths, 'message' => 'S3 paths saved successfully']);
    }

    public function getallProducts(){
        $productsWithUploads = Products::with('uploads')
        ->where('isActive', 1) // Add the where clause to filter by isActive
        ->get();

        return response()->json(['products' => $productsWithUploads]);
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Products::findOrFail($id);

        $product->update([
            'product_sku' => $request->productSku,
            'product_slug' => $request->productSlug,
            'product_name' => $request->productName,
            'meta_desc' => $request->metaDesc,
            'meta_keyword' => $request->metaKeyword,
            'short_description' => $request->shortDescription,
            'long_description' => $request->longDescription,
            'addi_info' => $request->addiInfo,
            'product-price' => $request->productPrice,
            'cat_id' => $request->catId,
            'isActive' => '1'
        ]);

        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }

    public function deleteProduct( $id){

        $product = Products::findOrFail($id);
        $product->update([
            'isActive' => '0'
        ]);

        return response()->json(['message' => 'Product Deleted successfully'], 200);

    }

    public function deleteImages($id){

        $upload = Uplodes::findOrFail($id);

        $upload->delete();

        return response()->json(['message' => 'File deleted successfully'], 200);

    }
}
