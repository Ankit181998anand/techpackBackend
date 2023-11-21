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
