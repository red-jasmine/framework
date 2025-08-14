<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 标记消息为已读请求
 */
class MessageMarkAsReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 消息ID（支持单个或多个）
            'message_ids' => ['required_without:mark_all', 'array'],
            'message_ids.*' => ['integer', 'min:1'],

            // 标记全部
            'mark_all' => ['nullable', 'boolean'],

            // 过滤条件（当mark_all为true时使用）
            'biz' => ['nullable', 'string', 'max:50'],
            'category_id' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'message_ids.required_without' => '消息ID或标记全部标志必须有一个',
            'message_ids.array' => '消息ID必须是数组',
            'message_ids.*.integer' => '消息ID必须是整数',
            'message_ids.*.min' => '消息ID必须大于0',
            'mark_all.boolean' => '标记全部标志必须是布尔值',
            'biz.string' => '业务线必须是字符串',
            'biz.max' => '业务线长度不能超过50个字符',
            'category_id.integer' => '分类ID必须是整数',
            'category_id.min' => '分类ID必须大于0',
        ];
    }

    protected function prepareForValidation(): void
    {
        // 处理布尔值
        $this->merge([
            'mark_all' => $this->boolean('mark_all'),
        ]);

        // 如果传入单个消息ID，转换为数组
        if ($this->has('message_id') && !$this->has('message_ids')) {
            $this->merge(['message_ids' => [$this->input('message_id')]]);
        }
    }

    /**
     * 验证规则
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // 如果既没有消息ID也没有标记全部，则报错
            if (!$this->input('message_ids') && !$this->input('mark_all')) {
                $validator->errors()->add('message_ids', '必须指定消息ID或选择标记全部');
            }

            // 如果标记全部且没有过滤条件，需要确认
            if ($this->input('mark_all') && !$this->input('biz') && !$this->input('category_id')) {
                // 这里可以添加额外的验证逻辑
            }
        });
    }
}
