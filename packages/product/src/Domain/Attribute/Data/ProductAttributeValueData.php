<?php

namespace RedJasmine\Product\Domain\Attribute\Data;

use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Product\Domain\Attribute\Rules\ProductAttributeExistsRule;
use RedJasmine\Product\Domain\Attribute\Rules\ProductAttributeGroupExistsRule;
use RedJasmine\Product\Domain\Attribute\Rules\ProductAttributeNameRule;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * 商品属性值数据传输对象
 *
 * @property string $name 名称
 * @property string|null $description 描述
 * @property ProductAttributeStatusEnum $status 状态
 * @property int $aid 属性ID
 * @property int $groupId 属性组ID
 * @property int $sort 排序
 * @property array|null $extra 扩展字段
 * @property array|null $translations 翻译数据数组
 */
class ProductAttributeValueData extends Data
{
    public string $name;
    public ?string $description = null;

    #[WithCast(EnumCast::class, ProductAttributeStatusEnum::class)]
    public ProductAttributeStatusEnum $status = ProductAttributeStatusEnum::ENABLE;

    public int $aid;
    public int $groupId = 0;
    public int $sort = 0;


    /**
     * @var ProductAttributeValueTranslationData[]|null
     */
    public ?array $translations = null;

    public static function attributes(): array
    {
        return [
            'name' => __('red-jasmine-product::product-attribute-value.fields.name'),
            'description' => __('red-jasmine-product::product-attribute-value.fields.description'),
            'status' => __('red-jasmine-product::product-attribute-value.fields.status'),
            'aid' => __('red-jasmine-product::product-attribute-value.fields.aid'),
            'group_id' => __('red-jasmine-product::product-attribute-value.fields.group_id'),
            'sort' => __('red-jasmine-product::product-attribute-value.fields.sort'),
            'extra' => __('red-jasmine-product::product-attribute-value.fields.extra'),
        ];
    }

    /**
     * 定义验证规则
     *
     * 使用 Laravel 标准的验证规则，包括自定义验证规则类
     * 这样可以充分利用 Spatie Laravel Data 的自动验证机制
     *
     * @param ValidationContext $context 验证上下文
     * @return array
     */
    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => ['required', 'string', 'max:64', new ProductAttributeNameRule()],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],
            'aid' => [
                'required',
                'integer',
                new ProductAttributeExistsRule(), // 使用 Laravel 验证规则验证属性是否存在
            ],
            'group_id' => [
                'sometimes',
                'nullable',
                'integer',
                new ProductAttributeGroupExistsRule(), // 使用 Laravel 验证规则验证属性组是否存在
            ],
            'sort' => ['integer'],
            'extra' => ['sometimes', 'nullable', 'array'],
        ];
    }
}

