<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('auth/login', 'App\Http\Controllers\Api\AuthController@login');

Route::group(['middleware' => ['apiJwt']], function() {
    Route::post('user/add', 'App\Http\Controllers\User\UserController@store');
    Route::get('user/getTotalBalance', 'App\Http\Controllers\User\UserController@getTotalBalance');
    Route::post('transaction/add', 'App\Http\Controllers\Transaction\TransactionController@store');
});