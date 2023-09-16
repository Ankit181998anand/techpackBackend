<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Helpers\Helper;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    //Login
    public function login(LoginRequest $request)
    {
        // login user
        if (!Auth::attempt($request->only('email', 'password'))) {
            Helper::sendError('Email Or Password is wroing !!!');
        }
        // send response
        return new UserResource(auth()->user());
    }


    //Register User
    public function register(RegisterRequest $request)
    {
        // register user
        $user = User::create([
            'username'          => $request->username,
            'first_name'          => $request->first_name,
            'last_name'          => $request->last_name,
            'email'         => $request->email,
            'password'      => bcrypt($request->password)
        ]);

        
        // assign role
        if($request->role == 'super'){
            $user_role = Role::where(['name' => 'SuperAdmin'])->first();
            $user->assignRole($user_role);
        }
        if ($request->role == 'admin') {
            $user_role = Role::where(['name' => 'Admin'])->first();
            $user->assignRole($user_role);
        }
        if ($request->role == 'user') {
            $user_role = Role::where(['name' => 'User'])->first();
            $user->assignRole($user_role);
        }
        
        // send response
        return response('User Registered Successfully', 200)->header('Content-Type', 'text/plain');
    }

    
}
