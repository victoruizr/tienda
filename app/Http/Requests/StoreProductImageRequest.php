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
                    'product.integer' => 'El identificador del producto debe ser un número entero.',
                    'product.exists' => 'El producto con el ID proporcionado no existe en la base de datos.',
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
            'url.required' => 'La URL de la imagen es obligatoria.',
            'url.url' => 'La URL de la imagen debe ser una URL válida.',
            'url.max' => 'La URL de la imagen no debe exceder los 255 caracteres.',
            'url.unique' => 'La URL de la imagen ya ha sido tomada.',
        ];
    }
}
