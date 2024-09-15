<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AproveArticleRequest extends FormRequest
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
            'article' => 'required|array|min:1',
        ];
    }
    public function messages(){
        return [
            'article.required' => 'You must select at least one article to approve.',
            'article.array' => 'The selected articles must be in an array format.',
            'article.min' => 'You must select at least one article to approve.',
        ];
    }
}
