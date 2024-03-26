<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['cors'])->post('/v1/admin/script-tag', 'Asset\TransactionController@createAdminScriptTag');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Asset')->middleware(['cors'])->group(function(){
    Route::get('/v1/transaction/{tranx_id?}', 'TransactionController@transaction');
    Route::post('/v1/transaction', 'TransactionController@create_bank_transaction');
    Route::post('/v1/generate/transaction', 'TransactionController@create_transaction');
});


Route::post('/payments/ebillsvalidate', function(Request $request) {
    return response()->json(['status' => true, 'success' => "hey"]);
});