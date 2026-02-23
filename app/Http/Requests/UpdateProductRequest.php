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
            'name.required' => 'The product name is required.',
            'name.string' => 'The name must be a text string.',
            'name.max' => 'The name cannot exceed 255 characters.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.gt' => 'The price must be greater than zero.',
            'stock.required' => 'The stock quantity is required.',
            'stock.integer' => 'The stock must be an integer.',
            'stock.min' => 'The stock cannot be negative.',
            'active.required' => 'The active status is required.',
            'active.boolean' => 'The active status must be true or false.',
            'category_id.required' => 'The category ID is required.',
            'category_id.exists' => 'The specified category does not exist.',
        ];
    }

}
