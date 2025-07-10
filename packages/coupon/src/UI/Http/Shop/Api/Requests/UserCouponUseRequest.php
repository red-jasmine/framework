<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\UI\Http\Shop\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 用户优惠券使用请求验证
 */
class UserCouponUseRequest extends FormRequest
{
    /**
     * 判断用户是否有权限进行该请求
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 获取验证规则
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_coupon_id' => ['required', 'integer', 'exists:user_coupons,id'],
            'order_id' => ['required', 'integer'],
            'order_no' => ['required', 'string', 'max:100'],
            'order_amount' => ['required', 'numeric', 'min:0'],
            'products' => ['nullable', 'array'],
            'products.*.id' => ['integer'],
            'products.*.amount' => ['numeric', 'min:0'],
            'products.*.quantity' => ['integer', 'min:1'],
        ];
    }

    /**
     * 获取验证错误消息
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'user_coupon_id.required' => '用户优惠券ID不能为空',
            'user_coupon_id.integer' => '用户优惠券ID必须是整数',
            'user_coupon_id.exists' => '用户优惠券不存在',
            'order_id.required' => '订单ID不能为空',
            'order_id.integer' => '订单ID必须是整数',
            'order_no.required' => '订单号不能为空',
            'order_no.string' => '订单号必须是字符串',
            'order_no.max' => '订单号长度不能超过100个字符',
            'order_amount.required' => '订单金额不能为空',
            'order_amount.numeric' => '订单金额必须是数字',
            'order_amount.min' => '订单金额不能小于0',
            'products.array' => '商品信息必须是数组',
            'products.*.id.integer' => '商品ID必须是整数',
            'products.*.amount.numeric' => '商品金额必须是数字',
            'products.*.amount.min' => '商品金额不能小于0',
            'products.*.quantity.integer' => '商品数量必须是整数',
            'products.*.quantity.min' => '商品数量不能小于1',
        ];
    }

    /**
     * 获取自定义属性名称
     * 
     * @return array
     */
    public function attributes(): array
    {
        return [
            'user_coupon_id' => '用户优惠券ID',
            'order_id' => '订单ID',
            'order_no' => '订单号',
            'order_amount' => '订单金额',
            'products' => '商品信息',
            'products.*.id' => '商品ID',
            'products.*.amount' => '商品金额',
            'products.*.quantity' => '商品数量',
        ];
    }

    /**
     * 准备验证数据
     * 
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'order_amount' => $this->float('order_amount'),
        ]);
    }
} 