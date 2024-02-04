<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResetPasswordController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);
Route::post('/forgot-password', [ResetPasswordController::class, 'forgotPassword']);
Route::post('/reset-password/{token}', [ResetPasswordController::class, 'resetPassword']);

Route::get('/student', [StudentController::class,'index']);
Route::get('/student/{id}', [StudentController::class,'show']);
Route::get('student/search/{city}',[StudentController::class,'search']);


Route::group(['middleware' => 'api'], function($routes){
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::post('/add-student',[StudentController::class,'store']);
    Route::put('/update-student/{id}',[StudentController::class,'update']);
    Route::delete('delete-student/{id}',[StudentController::class,'destroy']);
    Route::post('/logout',[UserController::class,'logout']);
});