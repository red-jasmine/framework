<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopicTagCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', 'topic-tag');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:200'],
            'color' => ['nullable', 'string', 'max:20'],
            'icon' => ['nullable', 'string', 'max:50'],
            'sort' => ['nullable', 'integer', 'min:0', 'max:999999'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '标签名称不能为空',
            'name.max' => '标签名称长度不能超过50个字符',
            'description.max' => '描述长度不能超过200个字符',
            'color.max' => '颜色值长度不能超过20个字符',
            'icon.max' => '图标名称长度不能超过50个字符',
            'sort.integer' => '排序必须是整数',
            'sort.min' => '排序不能小于0',
            'sort.max' => '排序不能大于999999',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort' => $this->integer('sort', 0),
        ]);
    }
}
