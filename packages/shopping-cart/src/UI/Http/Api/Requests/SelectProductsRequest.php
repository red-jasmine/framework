<?php

namespace RedJasmine\ShoppingCart\UI\Http\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identities' => 'required|array',
            'identities.*.shopType' => 'required|string',
            'identities.*.shopId' => 'required|integer',
            'identities.*.productType' => 'required|string',
            'identities.*.productId' => 'required|integer',
            'identities.*.skuId' => 'required|integer',
            'selected' => 'required|boolean',
        ];
    }
} 