<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/login/twitter', 'redirectToProvider');
    Route::get('/login/twitter/callback', 'handleProviderCallback');

    Route::get('/request_tweeter_oauth', 'requestOauth');
    Route::get('/request_tweeter_oauth/callback', 'handleOauthCallback');
});

Route::get('/', function () {
    return view('welcome');
});
