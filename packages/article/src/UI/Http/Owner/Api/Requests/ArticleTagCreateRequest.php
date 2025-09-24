<?php

namespace RedJasmine\Article\UI\Http\Owner\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 文章标签创建请求验证
 */
class ArticleTagCreateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:article_tags,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
            'color' => ['nullable', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'is_show' => ['boolean'],
        ];
    }

    /**
     * 错误消息
     */
    public function messages(): array
    {
        return [
            'name.required' => '标签名称不能为空',
            'name.max' => '标签名称长度不能超过255个字符',
            'slug.unique' => '标签别名已存在',
            'slug.max' => '标签别名长度不能超过255个字符',
            'description.max' => '标签描述长度不能超过1000个字符',
            'color.regex' => '标签颜色格式不正确，应为 #RRGGBB 格式',
            'color.max' => '标签颜色长度不能超过7个字符',
            'sort.integer' => '排序值必须是整数',
            'sort.min' => '排序值不能小于0',
        ];
    }

    /**
     * 准备验证数据
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_show' => $this->boolean('is_show', true),
            'sort' => $this->integer('sort', 0),
        ]);
    }
}
