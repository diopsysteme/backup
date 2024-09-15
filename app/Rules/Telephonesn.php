<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Telephonesn implements Rule
{
    /**
     * Détermine si la règle de validation passe.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^(77|76|78|70|75)\d{7}$/', $value);
    }

    /**
     * Obtenir le message de validation pour la règle.
     *
     * @return string
     */
    public function message()
    {
        return 'Le numéro de téléphone doit commencer par 77, 76, 78, 70 ou 75 et être suivi de 7 chiffres.';
    }
}
