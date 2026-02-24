<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DeleteProductRequest extends FormRequest
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
                    'product.integer' => 'El identificador del producto debe ser un número entero..',
                    'product.exists' => 'El producto con el ID proporcionado no existe en la base de datos.',
                ]
            );

            if ($idValidator->fails()) {
                $validator->errors()->merge($idValidator->errors());
            }
        });
    }
}
