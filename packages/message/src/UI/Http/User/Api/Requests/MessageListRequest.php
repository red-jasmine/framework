<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RedJasmine\Message\Domain\Models\Enums\MessagePriorityEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageStatusEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageTypeEnum;
use RedJasmine\Message\Domain\Models\Enums\PushStatusEnum;

/**
 * 消息列表请求
 */
class MessageListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 基础过滤
            'biz' => ['nullable', 'string', 'max:50'],
            'category_id' => ['nullable', 'integer', 'min:1'],
            'template_id' => ['nullable', 'integer', 'min:1'],
            'type' => ['nullable', 'string', 'in:' . implode(',', array_column(MessageTypeEnum::cases(), 'value'))],
            'priority' => ['nullable', 'string', 'in:' . implode(',', array_column(MessagePriorityEnum::cases(), 'value'))],
            'status' => ['nullable', 'string', 'in:' . implode(',', array_column(MessageStatusEnum::cases(), 'value'))],
            'push_status' => ['nullable', 'string', 'in:' . implode(',', array_column(PushStatusEnum::cases(), 'value'))],

            // 内容过滤
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],

            // 布尔过滤
            'is_urgent' => ['nullable', 'boolean'],
            'is_burn_after_read' => ['nullable', 'boolean'],
            'has_attachment' => ['nullable', 'boolean'],
            'is_expired' => ['nullable', 'boolean'],
            'is_high_priority' => ['nullable', 'boolean'],

            // 渠道过滤
            'channels' => ['nullable', 'array'],
            'channels.*' => ['string', 'in:in_app,push,email,sms'],

            // 时间范围过滤
            'created_start' => ['nullable', 'date'],
            'created_end' => ['nullable', 'date', 'after_or_equal:created_start'],
            'read_start' => ['nullable', 'date'],
            'read_end' => ['nullable', 'date', 'after_or_equal:read_start'],
            'expires_start' => ['nullable', 'date'],
            'expires_end' => ['nullable', 'date', 'after_or_equal:expires_start'],

            // 关联查询
            'include' => ['nullable', 'array'],
            'include.*' => ['string', 'in:category,template,pushLogs'],

            // 分页参数
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort' => ['nullable', 'string', 'regex:/^-?(id|title|created_at|updated_at|read_at|expires_at|priority)$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'biz.string' => '业务线必须是字符串',
            'biz.max' => '业务线长度不能超过50个字符',
            'category_id.integer' => '分类ID必须是整数',
            'category_id.min' => '分类ID必须大于0',
            'template_id.integer' => '模板ID必须是整数',
            'template_id.min' => '模板ID必须大于0',
            'type.in' => '消息类型无效',
            'priority.in' => '优先级无效',
            'status.in' => '状态无效',
            'push_status.in' => '推送状态无效',
            'title.string' => '标题必须是字符串',
            'title.max' => '标题长度不能超过255个字符',
            'content.string' => '内容必须是字符串',
            'is_urgent.boolean' => '紧急标志必须是布尔值',
            'is_burn_after_read.boolean' => '阅后即焚标志必须是布尔值',
            'has_attachment.boolean' => '附件标志必须是布尔值',
            'is_expired.boolean' => '过期标志必须是布尔值',
            'is_high_priority.boolean' => '高优先级标志必须是布尔值',
            'channels.array' => '渠道必须是数组',
            'channels.*.in' => '渠道类型无效',
            'created_start.date' => '创建开始时间格式无效',
            'created_end.date' => '创建结束时间格式无效',
            'created_end.after_or_equal' => '创建结束时间必须大于等于开始时间',
            'read_start.date' => '阅读开始时间格式无效',
            'read_end.date' => '阅读结束时间格式无效',
            'read_end.after_or_equal' => '阅读结束时间必须大于等于开始时间',
            'expires_start.date' => '过期开始时间格式无效',
            'expires_end.date' => '过期结束时间格式无效',
            'expires_end.after_or_equal' => '过期结束时间必须大于等于开始时间',
            'include.array' => '关联查询必须是数组',
            'include.*.in' => '关联查询类型无效',
            'page.integer' => '页码必须是整数',
            'page.min' => '页码必须大于0',
            'per_page.integer' => '每页数量必须是整数',
            'per_page.min' => '每页数量必须大于0',
            'per_page.max' => '每页数量不能超过100',
            'sort.regex' => '排序字段格式无效',
        ];
    }

    protected function prepareForValidation(): void
    {
        // 处理布尔值
        $this->merge([
            'is_urgent' => $this->boolean('is_urgent'),
            'is_burn_after_read' => $this->boolean('is_burn_after_read'),
            'has_attachment' => $this->boolean('has_attachment'),
            'is_expired' => $this->boolean('is_expired'),
            'is_high_priority' => $this->boolean('is_high_priority'),
        ]);

        // 设置默认值
        if (!$this->has('per_page')) {
            $this->merge(['per_page' => 15]);
        }

        if (!$this->has('sort')) {
            $this->merge(['sort' => '-created_at']);
        }
    }
}
