<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CustomPassword implements Rule
{
    public function passes($attribute, $value)
    {
        // Define the password rule with the desired criteria
        $passwordRule = Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols();
        // Use Laravel's Validator to validate the password
        $validator = Validator::make(
            [$attribute => $value], // Data to validate
            [$attribute => $passwordRule] // Apply the password rule
        );

        // Return whether the validation passes
        return !$validator->fails();
    }

    public function message()
    {
        return 'Le mot de passe doit contenir au moins 8 caractères, inclure des lettres, des majuscules, des chiffres, et des symboles.';
    }
}
