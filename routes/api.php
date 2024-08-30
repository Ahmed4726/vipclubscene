<?php

use Illuminate\Http\Request;
use League\Glide\Urls\UrlBuilderFactory;

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

Route::get('/secure-image', function (Request $request) {
    // dd('ok');
    $image = $request->query('image');
    $width = $request->query('width');
    $height = $request->query('height');
    
    // Get security key
    $signkey = env('APP_KEY');

    // Build URL
    $urlBuilder = UrlBuilderFactory::create('usermedia', $signkey);
    $url = $urlBuilder->getUrl($image, ['w' => $width, 'h' => $height, 'fit' => 'crop']);

    return response()->json(['url' => $url]);
});