<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * * @param int $category El ID de la categoría (Path Parameter)
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($this->route('category')),
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $categoryId = $this->route('category');

            $idValidator = Validator::make(
                ['category' => $categoryId], // Cambiado a 'category' para coincidir con la URL
                ['category' => 'required|integer|exists:categories,id'],
                [
                    'category.integer' => 'The category identifier must be an integer.',
                    'category.exists' => 'The category with the provided ID does not exist in the database.',
                ]
            );

            if ($idValidator->fails()) {
                $validator->errors()->merge($idValidator->errors());
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The category name is required.',
            'name.string' => 'The name must be a text string.',
            'name.max' => 'The name cannot exceed 255 characters.',
            'name.unique' => 'This category name is already in use.',
        ];
    }
}