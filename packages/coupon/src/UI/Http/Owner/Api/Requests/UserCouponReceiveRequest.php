<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\UI\Http\Owner\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 用户优惠券领取请求验证
 */
class UserCouponReceiveRequest extends FormRequest
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
            'coupon_id' => ['required', 'integer', 'exists:coupons,id'],
            'channel' => ['nullable', 'string', 'max:50'],
            'source' => ['nullable', 'string', 'max:100'],
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
            'coupon_id.required' => '优惠券ID不能为空',
            'coupon_id.integer' => '优惠券ID必须是整数',
            'coupon_id.exists' => '优惠券不存在',
            'channel.string' => '渠道必须是字符串',
            'channel.max' => '渠道长度不能超过50个字符',
            'source.string' => '来源必须是字符串',
            'source.max' => '来源长度不能超过100个字符',
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
            'coupon_id' => '优惠券ID',
            'channel' => '渠道',
            'source' => '来源',
        ];
    }

    /**
     * 准备验证数据
     * 
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // 可以在这里对数据进行预处理
    }
} 