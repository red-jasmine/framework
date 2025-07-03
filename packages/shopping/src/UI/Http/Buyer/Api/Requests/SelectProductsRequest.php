<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Requests;

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
            'selected' => 'required|boolean',
        ];
    }
} 