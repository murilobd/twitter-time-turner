<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        $token = $userCreated->createToken('twitter-oauth')->plainTextToken;

        return response()->json(['userCreated' => $userCreated, 'twitterUser' => $user], 200, ['Access-Token' => $token]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        $response = [
            'message' => 'logged out'
        ];

        return response($response, 201);
    }
}
