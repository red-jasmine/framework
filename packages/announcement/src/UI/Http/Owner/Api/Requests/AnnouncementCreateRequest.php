<?php

namespace RedJasmine\Announcement\UI\Http\Owner\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'biz' => ['required', 'string', 'max:50'],
            'category_id' => ['nullable', 'integer', 'exists:announcement_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'url', 'max:500'],
            'content_type' => ['required', 'string', 'in:text,rich,markdown'],
            'content' => ['required', 'string'],
            'publish_time' => ['nullable', 'date', 'after:now'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['string', 'max:500'],
            'is_force_read' => ['boolean'],
            'scopes' => ['nullable', 'array'],
            'scopes.*' => ['string'],
            'channels' => ['nullable', 'array'],
            'channels.*' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'biz.required' => '业务线不能为空',
            'biz.max' => '业务线长度不能超过50个字符',
            'category_id.exists' => '分类不存在',
            'title.required' => '标题不能为空',
            'title.max' => '标题长度不能超过255个字符',
            'image.url' => '图片必须是有效的URL',
            'image.max' => '图片URL长度不能超过500个字符',
            'content_type.required' => '内容类型不能为空',
            'content_type.in' => '内容类型必须是text、rich或markdown',
            'content.required' => '内容不能为空',
            'publish_time.date' => '发布时间必须是有效的日期',
            'publish_time.after' => '发布时间必须是未来时间',
            'attachments.array' => '附件必须是数组格式',
            'attachments.*.string' => '附件项必须是字符串',
            'attachments.*.max' => '附件项长度不能超过500个字符',
            'is_force_read.boolean' => '强制阅读必须是布尔值',
            'scopes.array' => '范围必须是数组格式',
            'scopes.*.string' => '范围项必须是字符串',
            'channels.array' => '渠道必须是数组格式',
            'channels.*.string' => '渠道项必须是字符串',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_force_read' => $this->boolean('is_force_read'),
            'attachments' => $this->input('attachments', []),
            'scopes' => $this->input('scopes', []),
            'channels' => $this->input('channels', []),
        ]);
    }
}
