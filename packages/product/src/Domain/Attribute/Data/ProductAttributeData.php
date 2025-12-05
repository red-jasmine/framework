<?php

namespace RedJasmine\Product\Domain\Attribute\Data;

use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeTypeEnum;
use RedJasmine\Product\Domain\Attribute\Rules\ProductAttributeNameRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * 商品属性数据传输对象
 *
 * @property string $name 名称
 * @property string|null $description 描述
 * @property string|null $unit 单位
 * @property ProductAttributeTypeEnum $type 类型
 * @property ProductAttributeStatusEnum $status 状态
 * @property int $groupId 属性组ID
 * @property int $sort 排序
 * @property bool $isRequired 是否必选
 * @property bool $isAllowMultiple 是否多值
 * @property bool $isAllowAlias 是否允许别名
 * @property array|null $translations 翻译数据数组
 */
class ProductAttributeData extends Data
{
    public string  $name;
    public ?string $description = null;
    public ?string $unit        = null;

    #[WithCast(EnumCast::class, ProductAttributeTypeEnum::class)]
    public ProductAttributeTypeEnum $type = ProductAttributeTypeEnum::SELECT;

    #[WithCast(EnumCast::class, ProductAttributeStatusEnum::class)]
    public ProductAttributeStatusEnum $status = ProductAttributeStatusEnum::ENABLE;

    public int  $groupId         = 0;
    public int  $sort            = 0;
    public bool $isRequired      = false;
    public bool $isAllowMultiple = false;
    public bool $isAllowAlias    = false;

    /**
     * @var ProductAttributeTranslationData[]|null
     */
    public ?array $translations = null;

    public static function attributes() : array
    {
        return [
            'name'              => __('red-jasmine-product::product-attribute.fields.name'),
            'type'              => __('red-jasmine-product::product-attribute.fields.type'),
            'unit'              => __('red-jasmine-product::product-attribute.fields.unit'),
            'description'       => __('red-jasmine-product::product-attribute.fields.description'),
            'sort'              => __('red-jasmine-product::product-attribute.fields.sort'),
            'group_id'          => __('red-jasmine-product::product-attribute.fields.group_id'),
            'is_required'       => __('red-jasmine-product::product-attribute.fields.is_required'),
            'is_allow_multiple' => __('red-jasmine-product::product-attribute.fields.is_allow_multiple'),
            'is_allow_alias'    => __('red-jasmine-product::product-attribute.fields.is_allow_alias'),
            'status'            => __('red-jasmine-product::product-attribute.fields.status'),
        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'name'              => ['required', 'string', 'max:64', new ProductAttributeNameRule()],
            'unit'              => ['sometimes', 'nullable', 'string', 'max:10'],
            'sort'              => ['integer'],
            'description'       => ['sometimes', 'nullable', 'string', 'max:255'],
            'group_id'          => [
                'sometimes', 'nullable', 'integer',
                // 领域验证已移至命令处理器中处理
            ],
            'is_required'       => ['boolean'],
            'is_allow_multiple' => ['boolean'],
            'is_allow_alias'    => ['boolean'],
        ];
    }
}

