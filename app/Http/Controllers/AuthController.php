<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Respose;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    // Register New Users
    public function register(Request $request){

        $fields = $request->validate([
            'name' => 'required|string', 
            'email' => 'required|string|unique:users,email', 
            'password' => 'required|string|confirmed', 
        ]);

        $user = User::create(
            [
                'name' => $fields['name'], 
                'email' => $fields['email'], 
                'password' => bcrypt($fields['password']), 
            ]);

        $token = $user->createToken('my_app_token');
        

        $response = [
            'user' => $user, 
            'token' => $token->plainTextToken
        ];

        return response($response,201);
    }




      // Login  Users
      public function login(Request $request){

        $fields = $request->validate([
            'email' => 'required|string', 
            'password' => 'required|string|confirmed', 
        ]);

        // Check Email
       $user = User::where('email', $fields['email'])->first();

       if(!$user || !Hash::check($fields['password'], $user->password)) {
             return response([
                'message' => 'Invalid Information',
             ],401);
       }
       

        $token = $user->createToken('my_app_token');
        
    
        $response = [
            'user' => $user, 
            'token' => $token->plainTextToken
        ];

        return response($response,201);
    }





    // Logout User
    public function logout(Request $request){

        auth()->user()->tokens()->delete();

        return [
            'message' => auth()->user()->name.' Logout Successfully',
        ];
    }




}
