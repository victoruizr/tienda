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
                    'category.integer' => 'El identificador de categoría debe ser un número entero.',
                    'category.exists' => 'La categoría con el ID proporcionado no existe en la base de datos.',
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
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'name.unique' => 'Este nombre de categoría ya está en uso.',
        ];
    }
}