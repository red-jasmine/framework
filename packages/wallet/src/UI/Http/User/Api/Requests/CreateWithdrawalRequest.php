<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateWithdrawalRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'wallet_type' => ['required', 'string'],
            //'amount' => ['required', 'numeric', 'min:0.01'],
            //'bank_card_id' => ['required', 'integer', 'exists:bank_cards,id'],
            //'remark' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages() : array
    {
        return [
            'wallet_type.required'  => '钱包类型不能为空',
            'amount.required'       => '提现金额不能为空',
            'amount.numeric'        => '金额必须是数字',
            'amount.min'            => '提现金额不能小于0.01',
            'bank_card_id.required' => '银行卡不能为空',
            'bank_card_id.exists'   => '银行卡不存在',
            'remark.max'            => '备注长度不能超过255字符',
        ];
    }

    protected function prepareForValidation() : void
    {
        $this->merge([
            'amount' => (float) $this->get('amount'),
        ]);
    }
} 