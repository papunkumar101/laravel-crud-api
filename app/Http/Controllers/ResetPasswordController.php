<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResetPassword;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Message;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Validator;

class ResetPasswordController extends Controller
{
    function forgotPassword(Request $request){
        $rules = [
            'email' => 'required|email',
        ];
        $validator = Validator::make($request->all(), $rules);
 
        if ($validator->fails()) {
            return response([
                'status' => 'fail',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user_mail = $request->email;
      //   Check user email exist or not 
      $user = User::where('email', $user_mail)->first();
      if(!$user){
          return response([
              'status' => 'failed',
              'message' => 'User not found',
          ],404);
      }
  
      // Generate a Token 
      $reset_token = Str::random(60);
  
      // Saving Data in table 
      ResetPassword::create([
          'email' => $user_mail,
          'token' => $reset_token,
          'created_at' => Carbon::now(),
      ]);
  
      // Send mail 
      Mail::send('reset',['token'=> $reset_token], function(Message $message)use($user_mail){
          $message->subject('Reset Your Password');
          $message->to($user_mail);
      });
  
      return response([
          'status' => 'success',
          'message' => 'Successfully sent mail, Please check tour mail.'
      ], 200);
      }
  
      function resetPassword(Request $request, $token){
          $formatted = Carbon::now()->subMinutes(3)->toDateTimeString();
          ResetPassword::where('created_at','<=',$formatted)->delete();
  
          $rules = [
            'password' => 'required|confirmed',
        ];
        $validator = Validator::make($request->all(), $rules);
 
        if ($validator->fails()) {
            return response([
                'status' => 'fail',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 400);
        }

          $passwordReset = ResetPassword::where('token', $token)->first();
           if(!$passwordReset){
              return response([
                  'status' => 'failed',
                  'message' => 'Token is Invalid or Expaired'
              ],400);
           }
  
           $user = User::where('email',$passwordReset->email)->first();
           $user->password = Hash::make($request->password);
           $user->save();
  
           ResetPassword::where('email',$user->email)->delete();
  
           return response([
              'status' => 'success',
              'message' => 'Successfully Changed Your Password'
           ],200);
      }
}
