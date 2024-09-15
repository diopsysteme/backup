<?php

namespace App\Traits;

use App\Enums\StatutEnum;

trait ApiResponseTrait
{
    public function sendResponse(StatutEnum $statut,  $data = null, string $message = '')
    {
        return response()->json([
            'success' => $statut,
            'message' => $message,
            'data' => $data
        ]);
    }
}