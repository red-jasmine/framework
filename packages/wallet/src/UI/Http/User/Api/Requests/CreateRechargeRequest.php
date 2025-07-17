<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRechargeRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'wallet_type'   => ['required', 'string'],
            'amount.amount' => ['required', 'numeric', 'min:0.01'],

        ];
    }

    public function messages() : array
    {
        return [
            'wallet_type.required'    => '钱包类型不能为空',
            'amount.amount.required'  => '充值金额不能为空',
            'amount.amount.numeric'   => '金额必须是数字',
            'amount.min'              => '充值金额不能小于1',
            'payment_method.required' => '支付方式不能为空',
            'remark.max'              => '备注长度不能超过255字符',
        ];
    }


} 