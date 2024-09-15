<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\StatutEnum;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;
use App\Providers\AuthenticateServiceProvider;

class AuthController
{
    protected $authProvider;

    public function __construct(AuthenticateServiceProvider $provider)
    {
        // Le provider détermine si Passport ou Sanctum sera utilisé
        $this->authProvider = $provider;
    }

    public function login(Request $request)
    {
        // Les identifiants sont récupérés depuis la requête
        $credentials = $request->only('login', 'password');
        
        // On délègue la logique de login à l'authProvider choisi
        return $this->authProvider->login($credentials);
    }

    public function logout(Request $request)
    {
        // On délègue la logique de logout à l'authProvider choisi
        return $this->authProvider->logout();
    }

    public function refresh(Request $request)
    {
        // On délègue la logique de refresh à l'authProvider choisi
        return $this->authProvider->refresh($request);
    }
    // public function revokeAll(Request $request)
    // {
    //     // Retrieve the authenticated user
    //     $user = Auth::guard('api')->user();

    //     if (!$user) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }
    //     // Revoke all access tokens
    //     $this->tokenRepository->revokeAllByUser($user->id);

    //     return response()->json(['message' => 'All tokens revoked successfully.'], 200);
    // }
}



// Prepare the response data
            // return $this->sendResponse(StatutEnum::SUCCESS, [
            //     'token' => $tokenResult,
            // ])->cookie('refresh_token', $refreshToken, 43200, null, null, true, true);