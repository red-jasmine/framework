<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RedJasmine\Message\Domain\Models\Enums\MessagePriorityEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageTypeEnum;

/**
 * 发送消息请求
 */
class MessageSendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 基础信息
            'biz' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required_without:template_id', 'string'],

            // 接收人
            'receivers' => ['required', 'array', 'min:1', 'max:100'],
            'receivers.*' => ['required', 'string'], // 用户ID

            // 分类和模板
            'category_id' => ['nullable', 'integer', 'min:1'],
            'template_id' => ['nullable', 'integer', 'min:1'],
            'template_variables' => ['nullable', 'array'],

            // 消息属性
            'type' => ['nullable', 'string', 'in:' . implode(',', array_column(MessageTypeEnum::cases(), 'value'))],
            'priority' => ['nullable', 'string', 'in:' . implode(',', array_column(MessagePriorityEnum::cases(), 'value'))],

            // 推送设置
            'channels' => ['nullable', 'array'],
            'channels.*' => ['string', 'in:in_app,push,email,sms'],
            'is_urgent' => ['nullable', 'boolean'],
            'is_burn_after_read' => ['nullable', 'boolean'],
            'expires_at' => ['nullable', 'date', 'after:now'],

            // 发送设置
            'send_immediately' => ['nullable', 'boolean'],
            'delay_seconds' => ['nullable', 'integer', 'min:0', 'max:86400'], // 最大延迟1天

            // 扩展数据
            'data' => ['nullable', 'array'],
            'push_parameters' => ['nullable', 'array'],
            'retry_config' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'biz.required' => '业务线不能为空',
            'biz.string' => '业务线必须是字符串',
            'biz.max' => '业务线长度不能超过50个字符',
            'title.required' => '消息标题不能为空',
            'title.string' => '标题必须是字符串',
            'title.max' => '标题长度不能超过255个字符',
            'content.required_without' => '消息内容或模板ID不能同时为空',
            'content.string' => '内容必须是字符串',
            'receivers.required' => '接收人不能为空',
            'receivers.array' => '接收人必须是数组',
            'receivers.min' => '至少需要一个接收人',
            'receivers.max' => '接收人数量不能超过100个',
            'receivers.*.required' => '接收人ID不能为空',
            'receivers.*.string' => '接收人ID必须是字符串',
            'category_id.integer' => '分类ID必须是整数',
            'category_id.min' => '分类ID必须大于0',
            'template_id.integer' => '模板ID必须是整数',
            'template_id.min' => '模板ID必须大于0',
            'template_variables.array' => '模板变量必须是数组',
            'type.in' => '消息类型无效',
            'priority.in' => '优先级无效',
            'channels.array' => '推送渠道必须是数组',
            'channels.*.in' => '推送渠道类型无效',
            'is_urgent.boolean' => '紧急标志必须是布尔值',
            'is_burn_after_read.boolean' => '阅后即焚标志必须是布尔值',
            'expires_at.date' => '过期时间格式无效',
            'expires_at.after' => '过期时间必须晚于当前时间',
            'send_immediately.boolean' => '立即发送标志必须是布尔值',
            'delay_seconds.integer' => '延迟秒数必须是整数',
            'delay_seconds.min' => '延迟秒数不能小于0',
            'delay_seconds.max' => '延迟秒数不能超过86400（1天）',
            'data.array' => '扩展数据必须是数组',
            'push_parameters.array' => '推送参数必须是数组',
            'retry_config.array' => '重试配置必须是数组',
        ];
    }

    protected function prepareForValidation(): void
    {
        // 处理布尔值
        $this->merge([
            'is_urgent' => $this->boolean('is_urgent'),
            'is_burn_after_read' => $this->boolean('is_burn_after_read'),
            'send_immediately' => $this->boolean('send_immediately', true), // 默认立即发送
        ]);

        // 设置默认值
        if (!$this->has('type')) {
            $this->merge(['type' => MessageTypeEnum::NOTIFICATION->value]);
        }

        if (!$this->has('priority')) {
            $this->merge(['priority' => MessagePriorityEnum::NORMAL->value]);
        }

        // 如果没有指定渠道，默认使用站内信
        if (!$this->has('channels') || empty($this->input('channels'))) {
            $this->merge(['channels' => ['in_app']]);
        }
    }

    /**
     * 获取接收人用户对象
     */
    public function getReceivers(): array
    {
        $receiverIds = $this->input('receivers', []);
        
        // 这里应该根据用户ID查询用户对象
        // 简化实现，实际应该调用用户服务
        return array_map(function ($receiverId) {
            return (object) ['id' => $receiverId, 'getKey' => fn() => $receiverId];
        }, $receiverIds);
    }
}
