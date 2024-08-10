<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller implements HasMiddleware
{


      public static function middleware()
     {
        return [
            new Middleware('auth:sanctum',except:['index','show'])
        ];
     }


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