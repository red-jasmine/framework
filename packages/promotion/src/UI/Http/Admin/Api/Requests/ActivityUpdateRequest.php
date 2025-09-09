<?php

namespace RedJasmine\Promotion\UI\Http\Admin\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityTypeEnum;

/**
 * 活动更新请求验证
 */
class ActivityUpdateRequest extends FormRequest
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
        $activity = $this->route('activity');
        $isRunning = $activity && $activity->status === ActivityStatusEnum::RUNNING;
        
        return [
            // 基本信息 - 运行中的活动不能修改某些字段
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'type' => $isRunning ? ['sometimes', 'string', 'in:' . $activity->type->value] : 
                     ['sometimes', 'string', 'in:' . implode(',', array_column(ActivityTypeEnum::cases(), 'value'))],
            
            // 客户端信息
            'client_type' => ['sometimes', 'nullable', 'string', 'max:50'],
            'client_id' => ['sometimes', 'nullable', 'integer'],
            
            // 时间设置 - 运行中的活动不能修改开始时间
            'sign_up_start_time' => ['sometimes', 'nullable', 'date'],
            'sign_up_end_time' => ['sometimes', 'nullable', 'date', 'after:sign_up_start_time'],
            'start_time' => $isRunning ? 'sometimes|date|in:' . $activity->start_time->format('Y-m-d H:i:s') :
                           ['sometimes', 'date'],
            'end_time' => ['sometimes', 'date', 'after:start_time'],
            
            // 商品要求 - 运行中的活动限制修改
            'product_requirements' => $isRunning ? 'sometimes|array' : ['sometimes', 'nullable', 'array'],
            'product_requirements.category_ids' => ['sometimes', 'nullable', 'array'],
            'product_requirements.category_ids.*' => ['integer'],
            'product_requirements.brand_ids' => ['sometimes', 'nullable', 'array'],
            'product_requirements.brand_ids.*' => ['integer'],
            'product_requirements.min_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'product_requirements.max_price' => ['sometimes', 'nullable', 'numeric', 'gt:product_requirements.min_price'],
            'product_requirements.exclude_category_ids' => ['sometimes', 'nullable', 'array'],
            'product_requirements.exclude_category_ids.*' => ['integer'],
            'product_requirements.exclude_product_ids' => ['sometimes', 'nullable', 'array'],
            'product_requirements.exclude_product_ids.*' => ['integer'],
            
            // 店铺要求
            'shop_requirements' => ['sometimes', 'nullable', 'array'],
            'shop_requirements.shop_ids' => ['sometimes', 'nullable', 'array'],
            'shop_requirements.shop_ids.*' => ['integer'],
            'shop_requirements.shop_types' => ['sometimes', 'nullable', 'array'],
            'shop_requirements.shop_types.*' => ['string'],
            'shop_requirements.exclude_shop_ids' => ['sometimes', 'nullable', 'array'],
            'shop_requirements.exclude_shop_ids.*' => ['integer'],
            
            // 用户要求
            'user_requirements' => ['sometimes', 'nullable', 'array'],
            'user_requirements.user_types' => ['sometimes', 'nullable', 'array'],
            'user_requirements.user_types.*' => ['string'],
            'user_requirements.user_levels' => ['sometimes', 'nullable', 'array'],
            'user_requirements.user_levels.*' => ['integer'],
            'user_requirements.new_user_only' => ['sometimes', 'boolean'],
            'user_requirements.member_only' => ['sometimes', 'boolean'],
            'user_requirements.exclude_user_ids' => ['sometimes', 'nullable', 'array'],
            'user_requirements.exclude_user_ids.*' => ['integer'],
            
            // 活动规则 - 运行中的活动限制修改核心规则
            'rules' => ['sometimes', 'nullable', 'array'],
            'rules.user_participation_limit' => $isRunning ? 'sometimes|integer|min:1' : 
                                               ['sometimes', 'nullable', 'integer', 'min:1'],
            'rules.product_purchase_limit' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'rules.allow_overlay' => ['sometimes', 'boolean'],
            'rules.new_user_only' => $isRunning ? 'sometimes|boolean|in:' . ($activity->rules->new_user_only ?? false) :
                                    ['sometimes', 'boolean'],
            'rules.member_only' => $isRunning ? 'sometimes|boolean|in:' . ($activity->rules->member_only ?? false) :
                                  ['sometimes', 'boolean'],
            'rules.min_purchase_amount' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'rules.max_discount_amount' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            
            // 叠加规则
            'overlay_rules' => ['sometimes', 'nullable', 'array'],
            'overlay_rules.allow_coupon' => ['sometimes', 'boolean'],
            'overlay_rules.allow_points' => ['sometimes', 'boolean'],
            'overlay_rules.allow_member_discount' => ['sometimes', 'boolean'],
            
            // 显示状态
            'is_show' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.max' => '活动标题不能超过255个字符',
            'type.in' => '活动类型无效',
            'start_time.in' => '运行中的活动不能修改开始时间',
            'end_time.after' => '结束时间必须晚于开始时间',
            'sign_up_end_time.after' => '报名结束时间必须晚于报名开始时间',
            'product_requirements.max_price.gt' => '最高价格必须大于最低价格',
            'rules.new_user_only.in' => '运行中的活动不能修改新用户限制',
            'rules.member_only.in' => '运行中的活动不能修改会员限制',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // 处理布尔值
        if ($this->has('is_show')) {
            $this->merge(['is_show' => $this->boolean('is_show')]);
        }
        
        // 处理规则中的布尔值
        if ($this->has('rules')) {
            $rules = $this->input('rules', []);
            if (isset($rules['allow_overlay'])) {
                $rules['allow_overlay'] = $this->boolean('rules.allow_overlay');
            }
            if (isset($rules['new_user_only'])) {
                $rules['new_user_only'] = $this->boolean('rules.new_user_only');
            }
            if (isset($rules['member_only'])) {
                $rules['member_only'] = $this->boolean('rules.member_only');
            }
            $this->merge(['rules' => $rules]);
        }
        
        // 处理叠加规则中的布尔值
        if ($this->has('overlay_rules')) {
            $overlayRules = $this->input('overlay_rules', []);
            if (isset($overlayRules['allow_coupon'])) {
                $overlayRules['allow_coupon'] = $this->boolean('overlay_rules.allow_coupon');
            }
            if (isset($overlayRules['allow_points'])) {
                $overlayRules['allow_points'] = $this->boolean('overlay_rules.allow_points');
            }
            if (isset($overlayRules['allow_member_discount'])) {
                $overlayRules['allow_member_discount'] = $this->boolean('overlay_rules.allow_member_discount');
            }
            $this->merge(['overlay_rules' => $overlayRules]);
        }
        
        // 处理用户要求中的布尔值
        if ($this->has('user_requirements')) {
            $userRequirements = $this->input('user_requirements', []);
            if (isset($userRequirements['new_user_only'])) {
                $userRequirements['new_user_only'] = $this->boolean('user_requirements.new_user_only');
            }
            if (isset($userRequirements['member_only'])) {
                $userRequirements['member_only'] = $this->boolean('user_requirements.member_only');
            }
            $this->merge(['user_requirements' => $userRequirements]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $activity = $this->route('activity');
            
            // 检查运行中活动的限制
            if ($activity && $activity->status === ActivityStatusEnum::RUNNING) {
                // 不能修改活动类型
                if ($this->has('type') && $this->input('type') !== $activity->type->value) {
                    $validator->errors()->add('type', '运行中的活动不能修改类型');
                }
                
                // 不能将结束时间改为早于当前时间
                if ($this->has('end_time') && strtotime($this->input('end_time')) < time()) {
                    $validator->errors()->add('end_time', '运行中的活动结束时间不能早于当前时间');
                }
            }
        });
    }
}

