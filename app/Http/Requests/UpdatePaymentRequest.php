<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'montant' => 'nullable|numeric|min:0.01',
            'date_paiement' => 'nullable|date',
            'moyen_paiement' => 'nullable|string'
        ];
    }
}
