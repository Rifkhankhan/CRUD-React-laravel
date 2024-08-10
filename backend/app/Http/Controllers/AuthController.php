<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {

        $feilds = $request->validate([
              'name' => 'required|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed',
        ]);

        $user = User::create($feilds);

        $token = $user -> createToken($request->name);


        return ['user' => $user,'token' => $token->plainTextToken];
    }

    public function login(Request $request) {
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
            ]);

            $user = User::where('email',$request->email)->first();

            if(!$user || !Hash::check($request->password,$user->password)) {
                return ['message' => 'The Provided credetials are not correct'];
            }

                
            $token = $user -> createToken($request->name);


            return ['user' => $user,'token' => $token->plainTextToken];

    }
    public function logout(Request $request) {
        
        $request->user()->token()->delete();
    }
}