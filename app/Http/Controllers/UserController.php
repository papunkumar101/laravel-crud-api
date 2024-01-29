<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('mytoken')->plainTextToken;

        return response([
            'status' => 'success',
            'message' => 'Register Successfully',
            'user' => $user,
            'token' => $token
        ],201);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password,$user->password)){
           return response([
            'status' => 'failed',
            'message' => 'Incorrect Credential !'
           ],401);
        }

        $token = $user->createToken('mytoken')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ],201);
    }

    function changePassword(Request $request){
      $request->validate([
        'password' => 'required|confirmed'
      ]);

      $loggedUser = auth()->user();
      $loggedUser->password = Hash::make($request->password);
      $loggedUser->save();

      return response([
        'status' => 'success',
        'message' => 'Successfully Changed Your Password'
      ],200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'status' => 'failed',
            'massage' => 'Successfully Logged Out !'
        ]);
    }
}
