<?php

namespace RedJasmine\Product\Domain\Attribute\Data;

use RedJasmine\Product\Domain\Attribute\Rules\ProductAttributeNameRule;
use RedJasmine\Support\Presets\Category\Domain\Data\BaseCategoryData;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * 商品属性组数据传输对象
 *
 * @property array|null $translations 翻译数据数组
 */
class ProductAttributeGroupData extends BaseCategoryData
{
    public static function rules(ValidationContext $context): array
    {
        $rules = parent::rules($context);

        // 添加名称验证规则
        $rules['name'] = ['required', 'string', 'max:100', new ProductAttributeNameRule()];

        return $rules;
    }
}

