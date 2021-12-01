<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('user/add', 'User\\UserController@store');
Route::get('user/getTotalBalance', 'User\\UserController@getTotalBalance');
Route::post('transaction/add', 'Transaction\\TransactionController@store');

