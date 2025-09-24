<?php

namespace RedJasmine\Article\UI\Http\Owner\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * 文章分类更新请求验证
 */
class ArticleCategoryUpdateRequest extends FormRequest
{
    /**
     * 权限验证
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 验证规则
     */
    public function rules(): array
    {
        $categoryId = $this->route('article_category');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('article_categories', 'slug')->ignore($categoryId)
            ],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'image' => ['sometimes', 'nullable', 'url', 'max:500'],
            'parent_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:article_categories,id',
                function ($attribute, $value, $fail) use ($categoryId) {
                    if ($value == $categoryId) {
                        $fail('分类不能设置自己为父分类');
                    }
                }
            ],
            'sort' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'is_show' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * 错误消息
     */
    public function messages(): array
    {
        return [
            'name.required' => '分类名称不能为空',
            'name.max' => '分类名称长度不能超过255个字符',
            'slug.unique' => '分类别名已存在',
            'slug.max' => '分类别名长度不能超过255个字符',
            'description.max' => '分类描述长度不能超过1000个字符',
            'image.url' => '分类图片必须是有效的URL',
            'image.max' => '分类图片URL长度不能超过500个字符',
            'parent_id.exists' => '父分类不存在',
            'sort.integer' => '排序值必须是整数',
            'sort.min' => '排序值不能小于0',
        ];
    }

    /**
     * 准备验证数据
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_show')) {
            $this->merge([
                'is_show' => $this->boolean('is_show'),
            ]);
        }

        if ($this->has('sort')) {
            $this->merge([
                'sort' => $this->integer('sort'),
            ]);
        }
    }
}
