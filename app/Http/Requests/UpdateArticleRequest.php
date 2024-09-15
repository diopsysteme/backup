<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
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
            'label' => ['sometimes','string','max:255','unique:articles,label'],
            'description' => ['sometimes','string','max:255'],
            'prix' => ['sometimes','numeric','min:0'],
            'qtstock' => ['sometimes','numeric','min:0'],
            'category' => ['sometimes','string']
        ];
    }
    public function messages()
{
    return [
        'label.string' => 'The label must be a string.',
        'label.max' => 'The label may not be greater than 255 characters.',
        'label.unique' => 'The label has already been taken. Please choose another one.',
        
        'description.string' => 'The description must be a string.',
        'description.max' => 'The description may not be greater than 255 characters.',
        
        'prix.numeric' => 'The price must be a number.',
        'prix.min' => 'The price must be at least 0.',
        
        'qtstock.numeric' => 'The stock quantity must be a number.',
        'qtstock.min' => 'The stock quantity must be at least 0.',
        
        'category.string' => 'The category must be a string.',
    ];
}
}
