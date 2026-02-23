<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreProductImageRequest extends FormRequest
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
            'url' => [
                'required',
                'url',
                'max:255',

            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $productId = $this->route('product');

            $idValidator = Validator::make(
                ['product' => $productId], // Cambiado a 'product' para coincidir con la URL
                ['product' => 'required|integer|exists:products,id'],
                [
                    'product.integer' => 'The product identifier must be an integer.',
                    'product.exists' => 'The product with the provided ID does not exist in the database.',
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
            'url.required' => 'The image URL is required.',
            'url.url' => 'The image URL must be a valid URL.',
            'url.max' => 'The image URL must not exceed 255 characters.',
            'url.unique' => 'The image URL has already been taken.',
        ];
    }
}
