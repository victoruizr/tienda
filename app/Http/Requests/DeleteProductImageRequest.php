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
                    'product.integer' => 'The product identifier must be an integer.',
                    'product.exists' => 'The product with the provided ID does not exist in the database.',
                    'imageId.integer' => 'The image ID must be an integer.',
                    'imageId.exists' => 'The image with the provided ID does not exist in the database.',
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
