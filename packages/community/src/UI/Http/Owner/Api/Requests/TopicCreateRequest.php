<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;

class TopicCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'content_type' => ['nullable', 'string', 'in:' . implode(',', ContentTypeEnum::values())],
            'image' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'category_id' => ['nullable', 'integer', 'exists:topic_categories,id'],
            'sort' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:topic_tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '标题不能为空',
            'title.max' => '标题长度不能超过255个字符',
            'content.required' => '内容不能为空',
            'content_type.in' => '内容类型无效',
            'image.url' => '图片必须是有效的URL',
            'image.max' => '图片URL长度不能超过500个字符',
            'description.max' => '描述长度不能超过1000个字符',
            'keywords.max' => '关键词长度不能超过500个字符',
            'category_id.exists' => '分类不存在',
            'sort.integer' => '排序必须是整数',
            'sort.min' => '排序不能小于0',
            'sort.max' => '排序不能大于999999',
            'tags.array' => '标签必须是数组',
            'tags.*.integer' => '标签ID必须是整数',
            'tags.*.exists' => '标签不存在',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort' => $this->integer('sort', 0),
            'content_type' => $this->input('content_type', ContentTypeEnum::TEXT->value),
        ]);
    }
}
