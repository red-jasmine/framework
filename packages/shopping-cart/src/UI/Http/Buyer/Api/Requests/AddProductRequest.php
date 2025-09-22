<?php

namespace RedJasmine\ShoppingCart\UI\Http\Buyer\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'market'              => 'required|string',
            'product.seller_type' => 'sometimes|string',
            'product.seller_id'   => 'sometimes|string',
            'product.type'        => 'required|string',
            'product.id'          => 'required|string',
            'product.sku_id'      => 'required|string',
            'quantity'            => 'required|integer|min:1',
        ];
    }
}


