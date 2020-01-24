<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:api')->namespace('Api')->group(function () {
    Route::resource('/products', 'ProductController', ['except' => 'edit','create']); 
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@website.com'], 404);
});

// Route::namespace('Api')->group(function () {
    // Route::post('/products', 'ProductController@store');
    // Route::get('/products/{id}', 'ProductController@show');    
    // Route::put('/products/{id}', 'ProductController@update');        
    // Route::delete('/products/{id}', 'ProductController@destroy');      
    //Route::resource('/products', 'ProductController')->except(['edit']);    
// });



