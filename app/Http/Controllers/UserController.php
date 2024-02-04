<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function register(Request $request){
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ];
 
        $messages = [
            'password.confirmed' => 'The password confirmation does not match.',
        ];
 
        $validator = Validator::make($request->all(), $rules, $messages);
 
        if ($validator->fails()) {
            return response([
                'status' => 'fail',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = auth()->login($user);

        return response([
            'status' => 'success',
            'message' => 'Register Successfully',
            'user' => $user,
            'token' => $token
        ],201);
    }

    public function login(Request $request){
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
 
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response([
                'status' => 'fail',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        // optional
        $user = User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password,$user->password)){
           return response([
            'status' => 'failed',
            'message' => 'Incorrect Credential !'
           ],401);
        }

        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);

        return response([
            'user' => $user,
            'token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60
        ],200);
    }

    function changePassword(Request $request){
        $rules = [
            'password' => 'required|confirmed',
            'email' => 'required|email'
        ];
 
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response([
                'status' => 'fail',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 400);
        }
         

    $user = User::where('email',$request->email)->first();
    if(!$user){
       return response([
        'status' => 'failed',
        'message' => 'Incorrect mail'
       ],401);
    }
      $user->password = Hash::make($request->password);
      $user->save();

      return response([
        'status' => 'success',
        'message' => 'Successfully Changed Your Password'
      ],200);
    }

    public function logout(){
        auth()->logout();
        return response([
            'status' => 'success',
            'massage' => 'Successfully Logged Out !'
        ],200);
    }
}
