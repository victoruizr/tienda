<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DeleteCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $categoryId = $this->route('category');


        $validator = Validator::make(['category_id' => $categoryId], [
    
            'category_id' => 'required|exists:categories,id',
        ],[
            'category_id.required' => 'Se requiere el ID de categoría..',
            'category_id.exists' => 'El ID de categoría debe existir en la tabla categorías.',
        ]);
        

        if($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function rules(): array
    {
        return [
    
        ];
    }
}
