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
use Illuminate\Http\Request;


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
        \Log::info('Register request received', ['request' => $request->all()]);
        // register user
        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'orders' => '0',
            'revenue' => '0'
        ]);

        // assign role based on request
        if ($request->role == 'super') {
            $user_role = Role::where('name', 'SuperAdmin')->first();
        } elseif ($request->role == 'admin') {
            $user_role = Role::where('name', 'Admin')->first();
        } elseif ($request->role == 'user') {
            $user_role = Role::where('name', 'User')->first();
        } else {
            // default role if no role specified in request
            $user_role = Role::where('name', 'User')->first();
        }

        $user->assignRole($user_role);

        // send response
        return response('User Registered Successfully', 200)->header('Content-Type', 'text/plain');
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function checkTokenExpiration(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        return response()->json(['expired' => !$user]);
    }

   


}
