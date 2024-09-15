<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\StatutEnum;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;

class VerifyAccessToken
{
    use ApiResponseTrait;
    protected $tokenRepository;

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the token from the Authorization header
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        // Authenticate the user using Passport's guard
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        Auth::setUser($user);
        // dd($user);
        // Pass user to the next middleware/handler
        $request->attributes->set('user', $user);

        return $next($request);
    }

}
