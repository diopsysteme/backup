<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetArtticleByLibelleRequest extends FormRequest
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
            'labell' => 'required_without:categorys|exists:articles,label|prohibits:categorys',
            'categorys' => 'required_without:labell|exists:articles,category|prohibits:labell',
            // Ensure 'prohibits' is applied directly to a specific field
        ];
    }
    
    public function messages(): array
    {
        return [
            'label.required_without' => 'Le champ label est obligatoire si la catégorie n\'est pas fournie.',
            'label.exists' => 'L\'article avec ce libellé n\'existe pas.',
            'category.required_without' => 'Le champ catégorie est obligatoire si le label n\'est pas fourni.',
            'category.exists' => 'L\'article avec cette catégorie n\'existe pas.',
            'label.prohibits' => 'Vous ne pouvez pas utiliser les champs label et catégorie en même temps.',
            'category.prohibits' => 'Vous ne pouvez pas utiliser les champs label et catégorie en même temps.',
        ];
    }
    
}
