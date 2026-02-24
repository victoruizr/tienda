<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UpdateProductRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|gt:0',
            'stock' => 'sometimes|required|integer|min:0',
            'active' => 'sometimes|required|boolean',
            'category_id' => 'sometimes|required|exists:categories,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $productId = $this->route('product');

            $idValidator = Validator::make(
                ['product' => $productId], 
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
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.string' => 'El nombre del producto debe ser una cadena de texto.',
            'name.max' => 'El nombre del producto no debe exceder los 255 caracteres.',
            'price.required' => 'El precio del producto es obligatorio.',
            'price.numeric' => 'El precio del producto debe ser un valor numérico.',
            'price.gt' => 'El precio del producto debe ser mayor que cero.',
            'stock.required' => 'La cantidad en stock del producto es obligatoria.',
            'stock.integer' => 'La cantidad en stock del producto debe ser un número entero.',
            'stock.min' => 'La cantidad en stock del producto no puede ser negativa.',
            'active.required' => 'El estado activo del producto es obligatorio.',
            'active.boolean' => 'El estado activo del producto debe ser verdadero o falso.',
            'category_id.required' => 'El ID de categoría es obligatorio.',
            'category_id.exists' => 'La categoría especificada no existe.',
        ];
    }

}
