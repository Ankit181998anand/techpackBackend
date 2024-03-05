<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
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

    public function update(Request $request, $id)
    {

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

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->update([
            'isActive' => '0'
        ]);

        return response()->json(['message' => 'Category deleted successfully']);


    }

    public function getCategoryIdsAndChildrenBySlug($categorySlug)
    {
        // Retrieve category ID based on the slug
        $categoryId = Category::where('cat_slug', $categorySlug)
            ->where('isActive', 1)
            ->value('id');

        if (!$categoryId) {
            // If category with the given slug is not found, return an empty response or handle the error as needed
            return response()->json(['message' => 'Category not found'], 404);
        }

        Log::info('catagory:', [$categoryId]);

        // Retrieve all categories
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


        // Helper function to recursively collect category IDs
        function collectCategoryIds($categories, $parentId)
        {
            $categoryIds = [];
            if (isset($categories[$parentId])) {
                foreach ($categories[$parentId] as $category) {
                    $categoryIds[] = $category['id'];
                    $categoryIds = array_merge($categoryIds, collectCategoryIds($categories, $category['id']));
                }
            }
            return $categoryIds;
        }

        // Collect category IDs starting from the specified category ID
        $categoryIds = collectCategoryIds($nestedCategories, $categoryId);

        if (empty($categoryIds)) {
            $categoryIds = [$categoryId];
        }

        // Initialize an empty array to store all products
        $allProducts = [];

        // Fetch products for each category ID and merge them into a single array
        foreach ($categoryIds as $id) {
            $productsWithImages = Products::where('cat_id', $id)
                ->where('isActive', 1)
                ->whereHas('images')
                ->whereHas('files')
                ->with('images')
                ->get()
                ->toArray();

            // Merge products into the allProducts array
            $allProducts = array_merge($allProducts, $productsWithImages);
        }

        return response()->json(['products' => $allProducts], 200);
    }

    public function getCategoryBySlug($categorySlug){

        $category = Category::where('cat_slug', $categorySlug)
                        ->where('isActive', 1)
                        ->first(); // Execute the query and get the first matching result
    
    // Check if category exists
    if ($category) {
        // Return the category data as JSON response
        return response()->json(['category' => $category], 200);
    } else {
        // Return a response indicating category not found
        return response()->json(['error' => 'Category not found'], 404);
    }
    }
}