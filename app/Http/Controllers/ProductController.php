<?php

namespace App\Http\Controllers;
use App\Models\Products;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    //

    public function store(Request $request){



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



    public function imageUpload(Request $request){

        //first Image
        $file1 = $request->file('image1');
        $fileName = time() . '_' . $file1->getClientOriginalName();

        //second Image
        $file2 = $request->file('image2');
        $fileName = time() . '_' . $file2->getClientOriginalName();

        //third Image
        $file3 = $request->file('image3');
        $fileName = time() . '_' . $file3->getClientOriginalName();

        try {
            $path1 = $file1->storeAs('images', $fileName, 's3');
            $path2 = $file2->storeAs('images', $fileName, 's3');
            $path3 = $file3->storeAs('images', $fileName, 's3');
        } catch (\Exception $e) {
            \Log::error('S3 Upload Error: ' . $e->getMessage());
            return response()->json(['error' => 'File upload failed.'], 500);
        }


        return response()->json(['path1' => 'https://techpack-frontend-test.s3.ap-south-1.amazonaws.com/'.$path1,
                                 'path2' => 'https://techpack-frontend-test.s3.ap-south-1.amazonaws.com/'.$path2,
                                 'path3' => 'https://techpack-frontend-test.s3.ap-south-1.amazonaws.com/'.$path3], 200);

    }

    public function fileUpload(Request $request){
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

    public function getallProducts(){
        $productsWithUploads = Products::where('isActive', 1)
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
}
