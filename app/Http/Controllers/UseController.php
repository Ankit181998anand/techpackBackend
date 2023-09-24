<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UseController extends Controller
{
    //

    public function getAllUsers(){

        $Users = User::with(['roles:name'])->get();

        return response()->json(['Users' => $Users], 200);

    }
}
