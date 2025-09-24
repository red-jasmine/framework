<?php

namespace RedJasmine\Article\UI\Http\Owner\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 文章创建请求验证
 */
class ArticleCreateRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'url', 'max:500'],
            'category_id' => ['nullable', 'integer', 'exists:article_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:article_tags,id'],
            'is_top' => ['boolean'],
            'is_show' => ['boolean'],
        ];
    }

    /**
     * 错误消息
     */
    public function messages(): array
    {
        return [
            'title.required' => '文章标题不能为空',
            'title.max' => '文章标题长度不能超过255个字符',
            'content.required' => '文章内容不能为空',
            'excerpt.max' => '文章摘要长度不能超过500个字符',
            'image.url' => '文章图片必须是有效的URL',
            'image.max' => '文章图片URL长度不能超过500个字符',
            'category_id.exists' => '文章分类不存在',
            'tags.array' => '文章标签必须是数组格式',
            'tags.*.integer' => '文章标签ID必须是整数',
            'tags.*.exists' => '文章标签不存在',
        ];
    }

    /**
     * 准备验证数据
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_top' => $this->boolean('is_top', false),
            'is_show' => $this->boolean('is_show', true),
        ]);
    }
}
