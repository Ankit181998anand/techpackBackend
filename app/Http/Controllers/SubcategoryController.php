<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Http\Requests\SubcatagoryRequest;

class SubcategoryController extends Controller
{
    //

    public function store(SubcatagoryRequest $request){

        $subCatagory = Subcategory::create([
            'subcat_name' => $request->subcatName,
            'subcat_slug' => $request->subcatSlug,
            'meta_desc' => $request->metaDesc,
            'meta_keyword' => $request->metaKeyword,
            'category_id' => $request->categoryId
        ]);

        return response()->json(['message' => 'Sub Category created successfully', 'category' => $subCatagory], 201);

    } 

}
