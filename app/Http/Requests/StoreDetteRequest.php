<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Article;

class StoreDetteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id', // Vérifie si le client existe
            'articles' => 'required|array|min:1',      // Le tableau articles ne doit pas être vide
            'montant_verse' => 'sometimes|numeric|gt:0',  // Montant versé optionnel, doit être numérique et supérieur à 0
        ];
    }
}
