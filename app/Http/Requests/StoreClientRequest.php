<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Telephonesn;
use App\Rules\CustomPassword;

class StoreClientRequest extends FormRequest
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
    return [
        'surnom' => 'required|string|max:255|unique:clients,surnom',
        'telephone' => ['required', 'string','unique:clients,telephone', new Telephonesn()],
        'category_id' => ['required', 'numeric','exists:categories,id'],
        'max_montant' => 'required|numeric|min:10',
        'address' => 'nullable|string|max:255',
        'user' => 'sometimes|array',
        'user.nom' => 'required_with:user|string|max:255',
        'user.prenom' => 'required_with:user|string|max:255',
        'user.login' => 'required_with:user|max:255|unique:users,login|email',
        'user.password' => ['required_with:user', 'confirmed', new CustomPassword()],
        'user.photo' => 'required_with:user|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100,max_width=3000,max_height=3000',
    ];



}

    /**
     * Messages de validation personnalisés.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'surnom.required' => 'Le champ surnom est obligatoire.',
            'surnom.string' => 'Le champ surnom doit être une chaîne de caractères.',
            'surnom.max' => 'Le champ surnom ne doit pas dépasser 255 caractères.',
            'surnom.unique' => 'Ce surnom est déjà utilisé.',
        
            'telephone.required' => 'Le champ téléphone est obligatoire.',
            'telephone.string' => 'Le champ téléphone doit être une chaîne de caractères.',
            'telephone.telephonesn' => 'Le numéro de téléphone n\'est pas valide.',
        
            'address.string' => 'Le champ adresse doit être une chaîne de caractères.',
            'address.max' => 'Le champ adresse ne doit pas dépasser 255 caractères.',
        
            'user.array' => 'Le champ user doit être un tableau.',
        
            'user.nom.required_with' => 'Le champ nom est obligatoire lorsque l\'utilisateur est présent.',
            'user.nom.string' => 'Le champ nom doit être une chaîne de caractères.',
            'user.nom.max' => 'Le champ nom ne doit pas dépasser 255 caractères.',
        
            'user.prenom.required_with' => 'Le champ prénom est obligatoire lorsque l\'utilisateur est présent.',
            'user.prenom.string' => 'Le champ prénom doit être une chaîne de caractères.',
            'user.prenom.max' => 'Le champ prénom ne doit pas dépasser 255 caractères.',
        
            'user.login.required_with' => 'Le champ login est obligatoire lorsque l\'utilisateur est présent.',
            'user.login.max' => 'Le champ login ne doit pas dépasser 255 caractères.',
            'user.login.unique' => 'Ce login est déjà utilisé.',
            'user.login.email' => 'Le champ login doit être une adresse e-mail valide.',
        
            'user.password.required_with' => 'Le champ mot de passe est obligatoire lorsque l\'utilisateur est présent.',
            'user.password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'user.password.custom_password' => 'Le mot de passe ne répond pas aux critères de sécurité requis.'

        ];
        
    }
}
