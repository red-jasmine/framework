<?php

namespace RedJasmine\ShoppingCart\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shopType' => 'required|string',
            'shopId' => 'required|integer',
            'productType' => 'required|string',
            'productId' => 'required|integer',
            'skuId' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'properties' => 'array',
        ];
    }
} 