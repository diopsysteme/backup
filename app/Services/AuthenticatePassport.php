<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\AuthenticateServiceProvider;
use Laravel\Passport\HasApiTokens;
class AuthenticatePassport implements AuthenticateServiceProvider
{
    use HasApiTokens;
    public function login(array $credentials)
    {
        $user = User::where('login', $credentials['login'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $tokenResult = $user->createToken('authToken');
            $accessToken = $tokenResult->accessToken;

            $refreshToken = Hash::make($user->id . Carbon::now());

            RefreshToken::create([
                'user_id' => $user->id,
                'token' => $refreshToken,
                'expires_at' => Carbon::now()->addDays(30),
            ]);

            return response()->json([
                'status' => 'success',
                'token' => $accessToken,
                'refresh_token' => $refreshToken,
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens->each(function ($token) {
            $token->delete();
        });

        RefreshToken::where('user_id', $user->id)->delete();

        return response()->json(['status' => 'success', 'message' => 'Logged out successfully']);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refresh_token');
        $storedToken = RefreshToken::where('token', $refreshToken)->first();

        if (!$storedToken || $storedToken->expires_at->isPast()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid or expired refresh token'], 401);
        }

        $user = $storedToken->user;
        $newAccessToken = $user->createToken('authToken')->accessToken;

        $newRefreshToken = Hash::make($user->id . Carbon::now());

        $storedToken->update([
            'token' => $newRefreshToken,
            'expires_at' => Carbon::now()->addDays(30),
        ]);

        return response()->json([
            'status' => 'success',
            'token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
        ]);
    }
}
