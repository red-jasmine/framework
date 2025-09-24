<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopicCategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', 'topic-category');
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'url', 'max:500'],
            'cluster' => ['nullable', 'string', 'max:50'],
            'parent_id' => ['nullable', 'integer', 'exists:topic_categories,id'],
            'sort' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_show' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '分类名称不能为空',
            'name.max' => '分类名称长度不能超过100个字符',
            'description.max' => '描述长度不能超过500个字符',
            'image.url' => '图片必须是有效的URL',
            'image.max' => '图片URL长度不能超过500个字符',
            'cluster.max' => '集群名称长度不能超过50个字符',
            'parent_id.exists' => '父分类不存在',
            'sort.integer' => '排序必须是整数',
            'sort.min' => '排序不能小于0',
            'sort.max' => '排序不能大于999999',
            'is_show.boolean' => '显示状态必须是布尔值',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort' => $this->integer('sort', 0),
            'is_show' => $this->boolean('is_show', true),
        ]);
    }
}
