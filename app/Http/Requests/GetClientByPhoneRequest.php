<?php

namespace App\Http\Requests;

use App\Rules\Telephonesn;
use Illuminate\Foundation\Http\FormRequest;

class GetClientByPhoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'telephone' => ['required','exists:clients,telephone',new Telephonesn()],
        ];
    }
    public function messages(): array{
        return [
            'telephone.required' => 'Le numéro de téléphone est obligatoire',
            'telephone.string' => 'Le numéro de téléphone doit être une chaîne de caractères',
            'telephone.max' => 'Le numéro de téléphone ne doit pas dépasser 255 caractères',
            'telephone.exists' => 'Aucun client ne correspond à ce numéro de téléphone',
        ];
    }
}
