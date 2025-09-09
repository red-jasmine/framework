<?php

namespace RedJasmine\Promotion\UI\Http\Admin\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityTypeEnum;

/**
 * 活动列表查询请求验证
 */
class ActivityListRequest extends FormRequest
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
            // 分页参数
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            
            // 排序参数
            'sort' => ['sometimes', 'string', 'in:id,title,type,status,start_time,end_time,created_at,updated_at,total_products,total_orders,total_sales'],
            'order' => ['sometimes', 'string', 'in:asc,desc'],
            
            // 搜索参数
            'title' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'string', 'in:' . implode(',', array_column(ActivityTypeEnum::cases(), 'value'))],
            'status' => ['sometimes', 'string', 'in:' . implode(',', array_column(ActivityStatusEnum::cases(), 'value'))],
            'is_show' => ['sometimes', 'boolean'],
            
            // 时间筛选
            'start_time_from' => ['sometimes', 'date'],
            'start_time_to' => ['sometimes', 'date', 'after_or_equal:start_time_from'],
            'end_time_from' => ['sometimes', 'date'],
            'end_time_to' => ['sometimes', 'date', 'after_or_equal:end_time_from'],
            'created_at_from' => ['sometimes', 'date'],
            'created_at_to' => ['sometimes', 'date', 'after_or_equal:created_at_from'],
            
            // 特殊筛选
            'running_only' => ['sometimes', 'boolean'], // 只显示进行中的活动
            'upcoming_only' => ['sometimes', 'boolean'], // 只显示即将开始的活动
            'expired_only' => ['sometimes', 'boolean'], // 只显示已过期的活动
            'draft_only' => ['sometimes', 'boolean'], // 只显示草稿活动
            
            // 统计筛选
            'min_products' => ['sometimes', 'integer', 'min:0'],
            'max_products' => ['sometimes', 'integer', 'gte:min_products'],
            'min_orders' => ['sometimes', 'integer', 'min:0'],
            'max_orders' => ['sometimes', 'integer', 'gte:min_orders'],
            'min_sales' => ['sometimes', 'numeric', 'min:0'],
            'max_sales' => ['sometimes', 'numeric', 'gte:min_sales'],
            
            // 关联筛选
            'category_id' => ['sometimes', 'integer'],
            'product_id' => ['sometimes', 'integer'],
            'user_id' => ['sometimes', 'integer'], // 创建者筛选
            
            // 包含关联数据
            'include' => ['sometimes', 'array'],
            'include.*' => ['string', 'in:products,participations'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'per_page.max' => '每页数量不能超过100',
            'sort.in' => '排序字段无效',
            'order.in' => '排序方式无效，只能是asc或desc',
            'type.in' => '活动类型无效',
            'status.in' => '活动状态无效',
            'start_time_to.after_or_equal' => '开始时间结束日期必须大于等于开始日期',
            'end_time_to.after_or_equal' => '结束时间结束日期必须大于等于开始日期',
            'created_at_to.after_or_equal' => '创建时间结束日期必须大于等于开始日期',
            'max_products.gte' => '商品数量上限必须大于等于下限',
            'max_orders.gte' => '订单数量上限必须大于等于下限',
            'max_sales.gte' => '销售额上限必须大于等于下限',
            'include.*.in' => '包含的关联数据无效',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // 处理布尔值
        $this->merge([
            'is_show' => $this->has('is_show') ? $this->boolean('is_show') : null,
            'running_only' => $this->has('running_only') ? $this->boolean('running_only') : null,
            'upcoming_only' => $this->has('upcoming_only') ? $this->boolean('upcoming_only') : null,
            'expired_only' => $this->has('expired_only') ? $this->boolean('expired_only') : null,
            'draft_only' => $this->has('draft_only') ? $this->boolean('draft_only') : null,
        ]);

        // 设置默认排序
        if (!$this->has('sort')) {
            $this->merge(['sort' => 'created_at']);
        }
        if (!$this->has('order')) {
            $this->merge(['order' => 'desc']);
        }

        // 设置默认分页
        if (!$this->has('per_page')) {
            $this->merge(['per_page' => 15]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // 检查互斥的筛选条件
            $exclusiveFilters = ['running_only', 'upcoming_only', 'expired_only', 'draft_only'];
            $activeFilters = array_filter($exclusiveFilters, fn($filter) => $this->boolean($filter));
            
            if (count($activeFilters) > 1) {
                $validator->errors()->add('filters', '不能同时使用多个互斥的筛选条件');
            }
        });
    }
}

