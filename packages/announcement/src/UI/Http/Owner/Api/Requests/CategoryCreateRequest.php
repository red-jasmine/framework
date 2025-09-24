<?php

namespace RedJasmine\Announcement\UI\Http\Owner\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'biz' => ['required', 'string', 'max:50'],
            'parent_id' => ['nullable', 'integer', 'exists:announcement_categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'url', 'max:500'],
            'cluster' => ['nullable', 'string', 'max:50'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'icon' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:20'],
            'is_show' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'biz.required' => '业务线不能为空',
            'biz.max' => '业务线长度不能超过50个字符',
            'parent_id.exists' => '父分类不存在',
            'name.required' => '分类名称不能为空',
            'name.max' => '分类名称长度不能超过100个字符',
            'description.max' => '描述长度不能超过500个字符',
            'image.url' => '图片必须是有效的URL',
            'image.max' => '图片URL长度不能超过500个字符',
            'cluster.max' => '集群长度不能超过50个字符',
            'sort.integer' => '排序必须是整数',
            'sort.min' => '排序不能小于0',
            'icon.max' => '图标长度不能超过100个字符',
            'color.max' => '颜色长度不能超过20个字符',
            'is_show.boolean' => '显示状态必须是布尔值',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_show' => $this->boolean('is_show', true),
            'sort' => $this->input('sort', 0),
        ]);
    }
}
