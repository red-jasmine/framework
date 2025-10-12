<?php

namespace RedJasmine\Product\Application\Attribute\Services\Commands;

use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Product\Domain\Attribute\Rules\ProductAttributeNameRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductAttributeValueCreateCommand extends Data
{
    public int                $pid;
    public string             $name;
    public ?string            $description = null;
    public int                $sort        = 0;
    public int                        $groupId = 0;
    public ProductAttributeStatusEnum $status  = ProductAttributeStatusEnum::ENABLE;


    public static function attributes(...$args) : array
    {

        return [
            'pid'         => __('red-jasmine-product::product-property-value.fields.pid'),
            'name'        => __('red-jasmine-product::product-property-value.fields.name'),
            'description' => __('red-jasmine-product::product-property-value.fields.description'),
            'sort'        => __('red-jasmine-product::product-property-value.fields.sort'),
            'group_id'    => __('red-jasmine-product::product-property-value.fields.group_id'),

        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'pid'         => [ 'required', 'integer' ],
            'name'        => [ 'required', 'max:64', new ProductAttributeNameRule() ],
            'sort'        => [ 'integer' ],
            'group_id'    => [ 'sometimes', 'nullable', 'integer' ],
            'description' => [ 'sometimes', 'nullable', 'string', 'max:255' ],

        ];
    }
}
