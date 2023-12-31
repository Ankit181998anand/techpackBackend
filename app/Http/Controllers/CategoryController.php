<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CatagoryRequest;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    //

    public function store(CatagoryRequest $request)
    {

        $category = Category::create([
            'cat_name' => $request->catName,
            'cat_slug' => $request->catSlug,
            'meta_desc' => $request->metaDesc,
            'meta_keyword' => $request->metaKeyword,
            'parent_id' => $request->parentId,
            'isActive' => '1'
        ]);

        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);

    }

    public function getAllCatagory()
    {

        $categories = Category::where('isActive', 1)->get();

        // Organize categories into a nested array based on parent_id
        $nestedCategories = [];
        foreach ($categories as $category) {
            $parentId = $category['parent_id'];
            if (!isset($nestedCategories[$parentId])) {
                $nestedCategories[$parentId] = [];
            }
            $nestedCategories[$parentId][] = $category;
        }

        // Helper function to recursively build the category tree
        function buildCategoryTree($categories, $parentId)
        {
            $tree = [];
            if (isset($categories[$parentId])) {
                foreach ($categories[$parentId] as $category) {
                    $category['children'] = buildCategoryTree($categories, $category['id']);
                    $tree[] = $category;
                }
            }
            return $tree;
        }

        // Build the category tree starting from the root categories (parent_id = 0)
        $categoryTree = buildCategoryTree($nestedCategories, 0);
       


        return response()->json(['categories' => $categoryTree], 200);

    }

    public function getCategoryById($categoryId)
    {
        $category = Category::find($categoryId);

        if ($category) {
            // Category found
            return response()->json(['category' => $category]);
        } else {
            // Category not found
            return response()->json(['error' => 'Category not found.'], 404);
        }
    }

    public function update(Request $request,$id){

        $category = Category::findOrFail($id);

        $category->update([
            'cat_name' => $request->catName,
            'cat_slug' => $request->catSlug,
            'meta_desc' => $request->metaDesc,
            'meta_keyword' => $request->metaKeyword,
            'parent_id' => $request->parentId,
            'isActive' => '1'
        ]);

        return response()->json(['message' => 'Category updated successfully']);

    }

    public function destroy($id){
        $category = Category::findOrFail($id);
        $category->update([
            'isActive' => '0'
        ]);

        return response()->json(['message' => 'Category deleted successfully']);


    }
}