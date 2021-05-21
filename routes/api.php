<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MiniURL;

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

Route::post('/miniurl/{url}', function (Request $request, $url) {
    $miniUrl = new MiniURL();
    return $miniUrl->short($url);
});

Route::get('/miniurl/{url}', function (Request $request, $url) {
    return $url;
});
