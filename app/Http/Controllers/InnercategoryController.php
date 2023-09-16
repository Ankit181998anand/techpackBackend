<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Innercategory;
use App\Http\Requests\InnercatagoryRequest;

class InnercategoryController extends Controller
{
    //

public function store(InnercatagoryRequest $request){

    $innerCatagory = Innercategory::create([

        'innercat_name' => $request->innercatName,
        'innersubcat_slug' => $request->innersubcatSlug,
        'meta_desc' => $request->metaDesc,
        'meta_keyword' => $request->metaKeyword,
        'subcategory_id' => $request->subcategoryId

    ]);

    return response()->json(['message' => 'Inner Category created successfully', 'category' => $innerCatagory], 201);

}

}
