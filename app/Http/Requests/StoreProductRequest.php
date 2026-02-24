<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|gt:0',
            'stock' => 'required|integer|min:0',
            'active' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.string' => 'El nombre del producto debe ser una cadena de texto.',
            'name.max' => 'El nombre del producto no debe exceder los 255 caracteres.',
            'price.required' => 'El precio del producto es obligatorio.',
            'price.numeric' => 'El precio del producto debe ser un valor numérico.',
            'price.gt' => 'El precio del producto debe ser mayor que 0.',
            'stock.required' => 'El stock del producto es obligatorio.',
            'stock.integer' => 'El stock del producto debe ser un número entero.',
            'stock.min' => 'El stock del producto debe ser al menos 0.',
            'active.required' => 'El estado activo del producto es obligatorio.',
            'active.boolean' => 'El estado activo del producto debe ser un valor booleano.',
            'category_id.required' => 'El ID de categoría es obligatorio.',
            'category_id.exists' => 'La categoría con el ID proporcionado no existe en la base de datos.',
        ];
    }
}
