<?php

namespace RedJasmine\Promotion\UI\Http\Admin\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityTypeEnum;

/**
 * 活动创建请求验证
 */
class ActivityCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // 基本信息
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', 'string', 'in:' . implode(',', array_column(ActivityTypeEnum::cases(), 'value'))],
            
            // 客户端信息
            'client_type' => ['nullable', 'string', 'max:50'],
            'client_id' => ['nullable', 'integer'],
            
            // 时间设置
            'sign_up_start_time' => ['nullable', 'date', 'after:now'],
            'sign_up_end_time' => ['nullable', 'date', 'after:sign_up_start_time'],
            'start_time' => ['required', 'date', 'after:now'],
            'end_time' => ['required', 'date', 'after:start_time'],
            
            // 商品要求
            'product_requirements' => ['nullable', 'array'],
            'product_requirements.category_ids' => ['nullable', 'array'],
            'product_requirements.category_ids.*' => ['integer'],
            'product_requirements.brand_ids' => ['nullable', 'array'],
            'product_requirements.brand_ids.*' => ['integer'],
            'product_requirements.min_price' => ['nullable', 'numeric', 'min:0'],
            'product_requirements.max_price' => ['nullable', 'numeric', 'gt:product_requirements.min_price'],
            'product_requirements.exclude_category_ids' => ['nullable', 'array'],
            'product_requirements.exclude_category_ids.*' => ['integer'],
            'product_requirements.exclude_product_ids' => ['nullable', 'array'],
            'product_requirements.exclude_product_ids.*' => ['integer'],
            
            // 店铺要求
            'shop_requirements' => ['nullable', 'array'],
            'shop_requirements.shop_ids' => ['nullable', 'array'],
            'shop_requirements.shop_ids.*' => ['integer'],
            'shop_requirements.shop_types' => ['nullable', 'array'],
            'shop_requirements.shop_types.*' => ['string'],
            'shop_requirements.exclude_shop_ids' => ['nullable', 'array'],
            'shop_requirements.exclude_shop_ids.*' => ['integer'],
            
            // 用户要求
            'user_requirements' => ['nullable', 'array'],
            'user_requirements.user_types' => ['nullable', 'array'],
            'user_requirements.user_types.*' => ['string'],
            'user_requirements.user_levels' => ['nullable', 'array'],
            'user_requirements.user_levels.*' => ['integer'],
            'user_requirements.new_user_only' => ['boolean'],
            'user_requirements.member_only' => ['boolean'],
            'user_requirements.exclude_user_ids' => ['nullable', 'array'],
            'user_requirements.exclude_user_ids.*' => ['integer'],
            
            // 活动规则
            'rules' => ['nullable', 'array'],
            'rules.user_participation_limit' => ['nullable', 'integer', 'min:1'],
            'rules.product_purchase_limit' => ['nullable', 'integer', 'min:1'],
            'rules.allow_overlay' => ['boolean'],
            'rules.new_user_only' => ['boolean'],
            'rules.member_only' => ['boolean'],
            'rules.min_purchase_amount' => ['nullable', 'numeric', 'min:0'],
            'rules.max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            
            // 叠加规则
            'overlay_rules' => ['nullable', 'array'],
            'overlay_rules.allow_coupon' => ['boolean'],
            'overlay_rules.allow_points' => ['boolean'],
            'overlay_rules.allow_member_discount' => ['boolean'],
            
            // 显示状态
            'is_show' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => '活动标题不能为空',
            'title.max' => '活动标题不能超过255个字符',
            'type.required' => '活动类型不能为空',
            'type.in' => '活动类型无效',
            'start_time.required' => '开始时间不能为空',
            'start_time.after' => '开始时间必须晚于当前时间',
            'end_time.required' => '结束时间不能为空',
            'end_time.after' => '结束时间必须晚于开始时间',
            'sign_up_end_time.after' => '报名结束时间必须晚于报名开始时间',
            'product_requirements.max_price.gt' => '最高价格必须大于最低价格',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_show' => $this->boolean('is_show', false),
        ]);
        
        // 处理规则中的布尔值
        if ($this->has('rules')) {
            $rules = $this->input('rules', []);
            $rules['allow_overlay'] = $this->boolean('rules.allow_overlay', false);
            $rules['new_user_only'] = $this->boolean('rules.new_user_only', false);
            $rules['member_only'] = $this->boolean('rules.member_only', false);
            $this->merge(['rules' => $rules]);
        }
        
        // 处理叠加规则中的布尔值
        if ($this->has('overlay_rules')) {
            $overlayRules = $this->input('overlay_rules', []);
            $overlayRules['allow_coupon'] = $this->boolean('overlay_rules.allow_coupon', true);
            $overlayRules['allow_points'] = $this->boolean('overlay_rules.allow_points', true);
            $overlayRules['allow_member_discount'] = $this->boolean('overlay_rules.allow_member_discount', true);
            $this->merge(['overlay_rules' => $overlayRules]);
        }
        
        // 处理用户要求中的布尔值
        if ($this->has('user_requirements')) {
            $userRequirements = $this->input('user_requirements', []);
            $userRequirements['new_user_only'] = $this->boolean('user_requirements.new_user_only', false);
            $userRequirements['member_only'] = $this->boolean('user_requirements.member_only', false);
            $this->merge(['user_requirements' => $userRequirements]);
        }
    }
}
