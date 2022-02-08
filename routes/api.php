<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TweetController;
use Laravel\Socialite\Facades\Socialite;

// Login/register are on web.php as socialite-twitter doesn't support stateless

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('tweet', TweetController::class);

    Route::post('/twitter', function (Request $request) {
        // return response()->json(["ok"]);
        $content = $request->input("content");
        $user = auth()->user();
        $twitter = $user->twitterConnection();
        $twitterPost = $twitter->post("statuses/update", ["status" => $content]);
        return response()->json($twitterPost, 200);
        // return $twitter->get("account/verify_credentials");
    });
});
