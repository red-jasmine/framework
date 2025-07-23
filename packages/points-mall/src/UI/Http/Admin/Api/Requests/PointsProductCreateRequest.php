<?php

namespace RedJasmine\PointsMall\UI\Http\Admin\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductStatusEnum;

class PointsProductCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'url'],
            'point' => ['required', 'integer', 'min:0'],
            'price_currency' => ['required', 'string', 'max:10'],
            'price_amount' => ['required', 'numeric', 'min:0'],
            'payment_mode' => ['required', 'string', 'in:' . implode(',', array_column(PointsProductPaymentModeEnum::cases(), 'value'))],
            'stock' => ['required', 'integer', 'min:0'],
            'lock_stock' => ['required', 'integer', 'min:0'],
            'safety_stock' => ['required', 'integer', 'min:0'],
            'exchange_limit' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:' . implode(',', array_column(PointsProductStatusEnum::cases(), 'value'))],
            'sort' => ['required', 'integer', 'min:0'],
            'category_id' => ['nullable', 'integer', 'exists:points_product_categories,id'],
            'product_type' => ['nullable', 'string', 'max:50'],
            'product_id' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '商品标题不能为空',
            'title.max' => '商品标题长度不能超过255个字符',
            'point.required' => '积分数量不能为空',
            'point.min' => '积分数量不能小于0',
            'price_amount.required' => '价格不能为空',
            'price_amount.min' => '价格不能小于0',
            'stock.required' => '库存不能为空',
            'stock.min' => '库存不能小于0',
            'exchange_limit.required' => '兑换限制不能为空',
            'exchange_limit.min' => '兑换限制不能小于0',
            'category_id.exists' => '分类不存在',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'price_amount' => (float) $this->input('price_amount', 0),
            'point' => (int) $this->input('point', 0),
            'stock' => (int) $this->input('stock', 0),
            'lock_stock' => (int) $this->input('lock_stock', 0),
            'safety_stock' => (int) $this->input('safety_stock', 0),
            'exchange_limit' => (int) $this->input('exchange_limit', 0),
            'sort' => (int) $this->input('sort', 0),
        ]);
    }
} 