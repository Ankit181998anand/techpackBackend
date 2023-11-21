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
}
