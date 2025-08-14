<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RedJasmine\Message\Domain\Models\Enums\StatusEnum;

/**
 * 创建消息分类请求
 */
class MessageCategoryCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'integer', 'min:1'],
            'biz' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'string', 'in:' . implode(',', array_column(StatusEnum::cases(), 'value'))],
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.integer' => '父分类ID必须是整数',
            'parent_id.min' => '父分类ID必须大于0',
            'biz.required' => '业务线不能为空',
            'biz.string' => '业务线必须是字符串',
            'biz.max' => '业务线长度不能超过50个字符',
            'name.required' => '分类名称不能为空',
            'name.string' => '分类名称必须是字符串',
            'name.max' => '分类名称长度不能超过100个字符',
            'description.string' => '描述必须是字符串',
            'description.max' => '描述长度不能超过500个字符',
            'icon.string' => '图标必须是字符串',
            'icon.max' => '图标长度不能超过100个字符',
            'color.string' => '颜色必须是字符串',
            'color.max' => '颜色长度不能超过20个字符',
            'sort.integer' => '排序必须是整数',
            'sort.min' => '排序不能小于0',
            'status.in' => '状态值无效',
        ];
    }

    protected function prepareForValidation(): void
    {
        // 设置默认值
        if (!$this->has('status')) {
            $this->merge(['status' => StatusEnum::ENABLE->value]);
        }

        if (!$this->has('sort')) {
            $this->merge(['sort' => 0]);
        }
    }
}
