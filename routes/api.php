<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Http\Controllers\AuthController;
use Laravel\Socialite\Facades\Socialite;

// Login/register are on web.php as socialite-twitter doesn't support stateless

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/twitter', function (Request $request) {
        $user = auth()->user();
        $twitter = $user->twitterConnection();
        $twitter->post("statuses/update", ["status" => "hello world, my name is {$user->name}"]);


        // return $twitter->get("account/verify_credentials");
    });
});
