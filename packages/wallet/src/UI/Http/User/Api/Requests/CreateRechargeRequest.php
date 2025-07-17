<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRechargeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'wallet_type' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string'],
            'remark' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'wallet_type.required' => '钱包类型不能为空',
            'amount.required' => '充值金额不能为空',
            'amount.numeric' => '金额必须是数字',
            'amount.min' => '充值金额不能小于1',
            'payment_method.required' => '支付方式不能为空',
            'remark.max' => '备注长度不能超过255字符',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'amount' => (float) $this->get('amount'),
        ]);
    }
} 