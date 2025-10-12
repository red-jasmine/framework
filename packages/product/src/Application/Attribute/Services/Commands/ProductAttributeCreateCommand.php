<?php

namespace RedJasmine\Product\Application\Attribute\Services\Commands;

use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeTypeEnum;
use RedJasmine\Product\Domain\Attribute\Rules\ProductAttributeNameRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductAttributeCreateCommand extends Data
{

    public string             $name;
    public ?string            $description     = null;
    public ?string            $unit;
    public int                $sort            = 0;
    public int                $groupId         = 0;
    public bool               $isRequired      = false;
    public bool               $isAllowMultiple = false;
    public bool                       $isAllowAlias = false;
    public ProductAttributeTypeEnum   $type         = ProductAttributeTypeEnum::SELECT;
    public ProductAttributeStatusEnum $status       = ProductAttributeStatusEnum::ENABLE;


    public static function attributes(...$args) : array
    {

        return [
            'name'              => __('red-jasmine-product::product-property.fields.name'),
            'type'              => __('red-jasmine-product::product-property.fields.type'),
            'unit'              => __('red-jasmine-product::product-property.fields.unit'),
            'description'       => __('red-jasmine-product::product-property.fields.description'),
            'sort'              => __('red-jasmine-product::product-property.fields.sort'),
            'group_id'          => __('red-jasmine-product::product-property.fields.group_id'),
            'is_required'       => __('red-jasmine-product::product-property.fields.is_allow_multiple'),
            'is_allow_multiple' => __('red-jasmine-product::product-property.fields.is_required'),
            'is_allow_alias'    => __('red-jasmine-product::product-property.fields.is_allow_alias'),
            'sort'              => __('red-jasmine-product::product-property.sort.name'),

        ];
    }

    public static function rules(ValidationContext $context) : array
    {

        return [
            'name'        => [ 'required', 'max:64', new ProductAttributeNameRule() ],
            'unit'        => [ 'sometimes', 'nullable', 'string', 'max:10', ],
            'sort'        => [ 'integer' ],
            'description' => [ 'sometimes', 'nullable', 'string', 'max:255' ],
            'group_id'    => [ 'sometimes', 'nullable', 'integer' ],

        ];
    }
}
