<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DeleteProductImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $productId = $this->route('product');
            $imageId = $this->route('imageId');

            $idValidator = Validator::make(
                ['product' => $productId, 'imageId' => $imageId],
                ['product' => 'required|integer|exists:products,id', 'imageId' => 'required|integer|exists:product_images,id'],
                [
                    'product.integer' => 'El identificador del producto debe ser un número entero..',
                    'product.exists' => 'El producto con el ID proporcionado no existe en la base de datos.',
                    'imageId.integer' => 'El identificador de la imagen debe ser un número entero.',
                    'imageId.exists' => 'La imagen con el ID proporcionado no existe en la base de datos.',
                ]
            );

            if ($idValidator->fails()) {
                $validator->errors()->merge($idValidator->errors());
            }
        });
    }

    public function rules(): array
    {
        return [

        ];
    }
}
