<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Abraham\TwitterOAuth\TwitterOAuth;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;

class AuthController extends Controller
{
    /**
     * Redirect the user to the Provider authentication page.
     *
     * @return JsonResponse
     */
    public function redirectToProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @return JsonResponse
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('twitter')->user();
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'status' => true,
                'twitter_user_id' => $user->getId(),
                'twitter_avatar' => $user->getAvatar()
            ]
        );

        $token = $userCreated->createToken('login-twitter')->plainTextToken;

        return redirect()->away(env('FRONTEND_URL') . '/login?access-token=' . $token, 302, ['Access-Token' => $token]);
        // return response()->json(['userCreated' => $userCreated, 'twitterUser' => $user], 200, ['Access-Token' => $token]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        $response = [
            'message' => 'logged out'
        ];

        return response($response, 201);
    }

    public function requestOauth()
    {
        // now it's time to get user's authorization to tweet using his account (https://developer.twitter.com/ja/docs/basics/authentication/overview/3-legged-oauth)
        $twitter = app('twitterclient');
        $tokens = $twitter->oauth("oauth/request_token", ['oauth_callback' => 'http://localhost/request_tweeter_oauth/callback']);
        if ($twitter->getLastHttpCode() == 200) {
            $url = $twitter->url("oauth/authorize", ["oauth_token" => $tokens['oauth_token'], 'oauth_callback' => 'http://localhost/request_tweeter_oauth/callback']);
            return $url;
        } else {
            dd($twitter);
            // Handle error case
        }
    }

    public function handleOauthCallback(Request $request)
    {
        $oauth_token = $request->query('oauth_token');
        $oauth_verifier = $request->query('oauth_verifier');

        $twitter = app('twitterclient');
        $access_token = $twitter->oauth("oauth/access_token", ["oauth_token" => $oauth_token, "oauth_verifier" => $oauth_verifier]);
        if ($twitter->getLastHttpCode() == 200) {
            $user = User::where('twitter_user_id', $access_token['user_id'])->first();
            $user->update([
                'twitter_oauth_token' => $access_token['oauth_token'],
                'twitter_oauth_token_secret' => $access_token['oauth_token_secret']
            ]);
            return response()->json(["ok"], 200);
        } else {
            dd($twitter);
            // Handle error case
        }
    }
}
