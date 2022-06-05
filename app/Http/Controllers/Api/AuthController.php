<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\User\InfoResource as UserInfoResource;

class AuthController extends Controller
{
    public function attemptLogin(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'These credentials do not match our records.'
            ], 404);
        }

        return response([
            'token' => $user->createToken('dts-token')->plainTextToken
        ], 201);
    }

    public function show(Request $request)
    {
        return new UserInfoResource($request->user());
    }

    public function redirectToProvider()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    public function handleProviderCallback()
    {
        $googleAccount = Socialite::driver('google')
            ->stateless()
            ->user();

        $user = User::firstOrCreate(
            ['email' => $googleAccount->getEmail()],
            [
                'email' => $googleAccount->getEmail(),
                'name' => $googleAccount->getName(),
            ]
        );

        $user->google_account()->updateOrCreate(
            [
                'google_id' => $googleAccount->getid()
            ],
            [
                'avatar' => $googleAccount->getAvatar()
            ]
        );

        return response([
            'token' => $user->createToken('dts-token')->plainTextToken
        ], 201);
    }
}
