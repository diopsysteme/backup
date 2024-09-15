<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CentralizeResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
        } else {
            $data = $response instanceof Arrayable ? $response->toArray() : (array) $response;
        }

        $status = 200;
        $message = 'Success';
        
        if (isset($data['statut']) && $data['statut'] === 'KO') {
            $status = $data['code'] ?? 400;
            $message = $data['message'] ?? 'Error';
        }

        if (empty($data)) {
            $status = 200;
            $message = 'Success';
        }

        return response()->json([
            'success' => $status === 200,
            'message' => $message,
            'data'    => $data
        ], $status);
    }
}
