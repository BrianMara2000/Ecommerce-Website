<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
            'remember' => 'boolean'
        ]);

        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'message' => 'Invalid credentials',
            ], 422);
        }

        $user = Auth::user();

        if (!$user->is_admin) {
            Auth::logout();

            return response([
                'message' => 'You don\'t have permission to authenticate as admin'
            ], 403);
        }

        if (!$user->email_verified_at) {
            Auth::logout();

            return response([
                'message' => 'Your email address is not verified yet.',
            ], 403);
        }

        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => new UserResource($user),
            'token' => $token,
            'message' => 'Logged in Successfully'
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response('Logged out successfully', 204);
    }

    public function getUser(Request $request)
    {
        return new UserResource($request->user());
    }
}
