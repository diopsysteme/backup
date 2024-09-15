<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'dette_id' => 'required|exists:dettes,id',
            'montant' => 'required|numeric|min:0.01',
            'moyen_paiement' => 'nullable|string',
        ];
    }
}

