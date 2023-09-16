<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CatagoryRequest;

class CategoryController extends Controller
{
    //

    public function store(CatagoryRequest $request){

        $category = Category::create([
            'cat_name' => $request->catName,
            'cat_slug' => $request->catSlug,
            'meta_desc' => $request->metaDesc,
            'meta_keyword' => $request->metaKeyword,
        ]);

        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);

    }
    
    public function getAllCatagory(){

        $categories = Category::with('subcategories.innercategories')->get();


        $categories->transform(function ($category) {
            $category->subcategories = $category->subcategories
                ->sortBy(function ($subcategory) {
                    return $subcategory->innercategories_count;
                });
            return $category;
        });

        return response()->json(['categories' => $categories], 200);

    }
}
