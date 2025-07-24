<?php

namespace RedJasmine\PointsMall\UI\Http\Admin\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PointProductCategoryCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:points_product_categories,id'],
            'sort' => ['integer', 'min:0'],
            'is_show' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '分类名称不能为空',
            'name.max' => '分类名称不能超过255个字符',
            'parent_id.exists' => '父级分类不存在',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_show' => $this->boolean('is_show'),
        ]);
    }
} 