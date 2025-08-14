<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RedJasmine\Message\Domain\Models\Enums\MessageTypeEnum;
use RedJasmine\Message\Domain\Models\Enums\StatusEnum;

/**
 * 创建消息模板请求
 */
class MessageTemplateCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'integer', 'min:1'],
            'biz' => ['required', 'string', 'max:50'],
            'code' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9_-]+$/'],
            'name' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'type' => ['nullable', 'string', 'in:' . implode(',', array_column(MessageTypeEnum::cases(), 'value'))],
            'description' => ['nullable', 'string', 'max:500'],
            'variables' => ['nullable', 'array'],
            'variables.*.name' => ['required', 'string', 'max:50'],
            'variables.*.label' => ['nullable', 'string', 'max:100'],
            'variables.*.type' => ['nullable', 'string', 'in:string,number,boolean,array,object'],
            'variables.*.required' => ['nullable', 'boolean'],
            'variables.*.default' => ['nullable'],
            'variables.*.description' => ['nullable', 'string', 'max:200'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'string', 'in:' . implode(',', array_column(StatusEnum::cases(), 'value'))],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.integer' => '分类ID必须是整数',
            'category_id.min' => '分类ID必须大于0',
            'biz.required' => '业务线不能为空',
            'biz.string' => '业务线必须是字符串',
            'biz.max' => '业务线长度不能超过50个字符',
            'code.required' => '模板编码不能为空',
            'code.string' => '模板编码必须是字符串',
            'code.max' => '模板编码长度不能超过100个字符',
            'code.regex' => '模板编码只能包含字母、数字、下划线和横线',
            'name.required' => '模板名称不能为空',
            'name.string' => '模板名称必须是字符串',
            'name.max' => '模板名称长度不能超过100个字符',
            'title.required' => '模板标题不能为空',
            'title.string' => '模板标题必须是字符串',
            'title.max' => '模板标题长度不能超过255个字符',
            'content.required' => '模板内容不能为空',
            'content.string' => '模板内容必须是字符串',
            'type.in' => '模板类型无效',
            'description.string' => '描述必须是字符串',
            'description.max' => '描述长度不能超过500个字符',
            'variables.array' => '变量必须是数组',
            'variables.*.name.required' => '变量名称不能为空',
            'variables.*.name.string' => '变量名称必须是字符串',
            'variables.*.name.max' => '变量名称长度不能超过50个字符',
            'variables.*.label.string' => '变量标签必须是字符串',
            'variables.*.label.max' => '变量标签长度不能超过100个字符',
            'variables.*.type.in' => '变量类型无效',
            'variables.*.required.boolean' => '变量必填标志必须是布尔值',
            'variables.*.description.string' => '变量描述必须是字符串',
            'variables.*.description.max' => '变量描述长度不能超过200个字符',
            'sort.integer' => '排序必须是整数',
            'sort.min' => '排序不能小于0',
            'status.in' => '状态值无效',
        ];
    }

    protected function prepareForValidation(): void
    {
        // 设置默认值
        if (!$this->has('type')) {
            $this->merge(['type' => MessageTypeEnum::NOTIFICATION->value]);
        }

        if (!$this->has('status')) {
            $this->merge(['status' => StatusEnum::ENABLE->value]);
        }

        if (!$this->has('sort')) {
            $this->merge(['sort' => 0]);
        }

        // 处理变量中的布尔值
        if ($this->has('variables') && is_array($this->input('variables'))) {
            $variables = [];
            foreach ($this->input('variables') as $variable) {
                if (isset($variable['required'])) {
                    $variable['required'] = filter_var($variable['required'], FILTER_VALIDATE_BOOLEAN);
                }
                $variables[] = $variable;
            }
            $this->merge(['variables' => $variables]);
        }
    }
}
