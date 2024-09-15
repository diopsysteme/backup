<?php

namespace App\Http\Requests;

use App\Rules\CustomPassword;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
{
    return [
        'user' => 'required|array',
        'user.nom' => 'required|string|max:255',
        'user.prenom' => 'required|string|max:255',
        'user.login' => 'required|max:255|unique:users,login|email',
        'user.password' => ['required', 'confirmed', new CustomPassword()],
        'user.photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=100,min_height=100,max_width=3000,max_height=3000',
        'idrole' => 'required_without:idclient|numeric|exists:roles,id|prohibits:idclient',
        'idclient' => 'required_without:idrole|numeric|exists:clients,id|prohibits:idrole',
    ];
}

public function messages()
{
    return [
        'nom.required' => 'Le nom est requis.',
        'nom.string' => 'Le nom doit être une chaîne de caractères.',
        'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',

        'prenom.required' => 'Le prénom est requis.',
        'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
        'prenom.max' => 'Le prénom ne peut pas dépasser 255 caractères.',

        'login.required' => 'Le login est requis.',
        'login.string' => 'Le login doit être une chaîne de caractères.',
        'login.unique' => 'Ce login est déjà utilisé.',

        'password.required' => 'Le mot de passe est requis.',
        'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',

        'password_confirmation.required_with' => 'La confirmation du mot de passe est requise lorsque le mot de passe est présent.',
        'password_confirmation.same' => 'La confirmation du mot de passe doit correspondre au mot de passe.',

        'idrole.prohibits' => 'L\'identifiant du rôle ne peut pas être présent en même temps que l\'identifiant client.',
        'idclient.prohibits' => 'L\'identifiant client ne peut pas être présent en même temps que l\'identifiant du rôle.',

        'idrole.numeric' => 'L\'identifiant du rôle doit être un nombre.',
        'idrole.exists' => 'Le rôle sélectionné est invalide.',

        'idclient.numeric' => 'L\'identifiant client doit être un nombre.',
        'idclient.exists' => 'Le client sélectionné est invalide.',

        'photo.image' => 'Le fichier doit être une image.',
        'photo.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
        'photo.max' => 'L\'image ne peut pas dépasser 2 Mo.',
        'photo.dimensions' => 'L\'image doit avoir des dimensions minimales de 100x100 pixels et maximales de 3000x3000 pixels.',
    ];
}

}
