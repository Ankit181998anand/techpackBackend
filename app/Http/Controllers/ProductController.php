<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Image;
use App\Models\File;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class ProductController extends Controller
{
    //

    public function store(Request $request)
    {



        $products = Products::create([
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

        return response()->json(['message' => 'products created successfully', 'products' => $products], 201);
    }



    public function imageUpload(Request $request)
    {

        $s3Paths = [];

        // Define the files and their respective names
        $files = [
            'image1' => 'image1',
            'image2' => 'image2',
            'image3' => 'image3'
        ];
        $count = 0;

        try {
            foreach ($files as $requestFile => $fileName) {
                if ($request->hasFile($requestFile)) {
                    $count++;
                    $file = $request->file($requestFile);
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    \Log::info($fileName);
                    $path = $file->storeAs('images', $fileName, 's3');
                    \Log::info($path);
                    $s3Paths[] = 'https://techpack-files.s3.ap-south-1.amazonaws.com/' . $path;

                    // Use the $requestFile (e.g., 'image1', 'image2') as the image_type
                    Image::create([
                        'image_path' => $s3Paths[count($s3Paths) - 1], // Use the last added path
                        'product_id' => $request->productId,
                        'isActive' => "1",
                        'image_type' => $requestFile
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Upload and Save Error: ' . $e->getMessage());
            return response()->json(['error' => 'File upload or database save failed.'], 500);
        }

        return response()->json(['paths' => $s3Paths, 'message' => 'S3 paths and database records saved successfully']);
    }

    public function fileUpload(Request $request)
    {
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        try {
            $path = $file->storeAs('Files', $fileName, 's3');
        } catch (\Exception $e) {
            \Log::error('S3 Upload Error: ' . $e->getMessage());
            return response()->json(['error' => 'File upload failed.'], 500);
        }

        File::create([
            'file_path' => 'https://techpack-files.s3.ap-south-1.amazonaws.com/' . $path,
            'product_id' => $request->productId,
            'isActive' => "1"
        ]);


        return response()->json(['path' => 'https://techpack-files.s3.ap-south-1.amazonaws.com/' . $path], 200);

    }

    public function getallProducts()
    {
        $productsWithImages = Products::where('isActive', 1)
            ->with(['images', 'files']) // Eager load the images relationship
            ->get();

        // Manually fetch the category details for each product
        foreach ($productsWithImages as $product) {
            $category = DB::table('categories')->select('cat_name')->where('id', $product->cat_id)->first();
            $product->cat_name = $category->cat_name;
        }

        return response()->json(['products' => $productsWithImages]);
    }

    public function getallProductsPublic()
{
    $productsWithImagesAndFiles = Products::where('isActive', 1)
        ->whereHas('images') // Ensure that the product has at least one image
        ->whereHas('files')  // Ensure that the product has at least one file
        ->with(['images', 'files']) // Eager load the images and files relationships
        ->get();

    // Manually fetch the category details for each product
    foreach ($productsWithImagesAndFiles as $product) {
        $category = DB::table('categories')->select('cat_name')->where('id', $product->cat_id)->first();
        $product->cat_name = $category->cat_name;
    }

    return response()->json(['products' => $productsWithImagesAndFiles]);
}


    public function getProductById($productId)
    {
        // Assuming $productId is the product ID you want to retrieve
        $productWithImages = Products::where('id', $productId)
            ->with(['images', 'files']) // Eager load the images relationship
            ->first();

        $category = DB::table('categories')->select('cat_name')->where('id', $productWithImages->cat_id)->first();

        // Include the category details in the response
        $productWithImages->cat_name = $category->cat_name;

        if (!$productWithImages) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        return response()->json(['product' => $productWithImages]);
    }

    public function getProductsByCategoryId($categoryId)
    {
        // Check if the category exists
        $category = DB::table('categories')->find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        // Fetch products with images for the specified category
        $productsWithImages = Products::where('cat_id', $categoryId)
            ->where('isActive', 1)
            ->with('images')
            ->get();

        if ($productsWithImages->isEmpty()) {
            return response()->json(['message' => 'No active products found for the given category.'], 404);
        }

        return response()->json(['products' => $productsWithImages]);
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

    public function deleteProduct($id)
    {

        $product = Products::findOrFail($id);
        $product->update([
            'isActive' => '0'
        ]);

        return response()->json(['message' => 'Product Deleted successfully'], 200);

    }

    public function getImagesByProductId($productId)
    {
        $images = Image::where('product_id', $productId)->get();

        return response()->json(['images' => $images]);
    }

    public function deleteImage($imageId)
    {
        try {
            $image = Image::findOrFail($imageId);
            $image->delete();

            return response()->json(['message' => 'Image deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Image not found or could not be deleted'], 404);
        }
    }

    public function getFileByProductId($productId)
    {
        $images = File::where('product_id', $productId)->get();

        return response()->json(['images' => $images]);
    }

    public function deleteFile($imageId)
    {
        try {
            $image = File::findOrFail($imageId);
            $image->delete();

            return response()->json(['message' => 'File deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'File not found or could not be deleted'], 404);
        }
    }
}
