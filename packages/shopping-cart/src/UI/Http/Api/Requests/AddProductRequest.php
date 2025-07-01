<?php

namespace RedJasmine\ShoppingCart\UI\Http\Api\Requests;

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
            'market'               => 'required|string',
            'product.shop_type'    => 'required|string',
            'product.shop_id'      => 'required|string',
            'product.product_type' => 'required|string',
            'product.product_id'   => 'required|string',
            'product.sku_id'       => 'required|string',
            'quantity'             => 'required|integer|min:1',

        ];
    }
} 