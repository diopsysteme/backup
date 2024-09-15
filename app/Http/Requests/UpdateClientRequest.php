<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Telephonesn;
use App\Rules\CustomPassword;

class UpdateClientRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Autoriser la requête pour tous les utilisateurs
    }

    /**
     * Règles de validation pour la requête.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'surnom' => 'sometimes|string|max:255',
            'telephone' => ['sometimes', 'string', new Telephonesn()],
            'address' => 'sometimes|string|max:255',
        ];

        if ($this->input('user')) {
            $rules['user'] = 'required|array';
            $rules['user.nom'] = 'sometimes|string|max:255';
            $rules['user.prenom'] = 'sometimes|string|max:255';
            $rules['user.email'] = [
                'sometimes', 
                'email', 
                'max:255', 
                'unique:users,email,' . $this->user()->id
            ];
            $rules['user.password'] = ['sometimes', 'confirmed', new CustomPassword()];
            $rules['user.password_confirmation'] = 'sometimes|required_with:user.password|same:user.password';
        }

        return $rules;
    }

    /**
     * Messages de validation personnalisés.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'telephone.regex' => 'Le numéro de téléphone doit commencer par 77, 76, 78, 70 ou 75 et être suivi de 7 chiffres.',
            'user.email.unique' => 'L\'adresse e-mail est déjà utilisée.',
            'user.password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'user.password_confirmation.required_with' => 'La confirmation du mot de passe est requise si le mot de passe est fourni.',
        ];
    }
}
