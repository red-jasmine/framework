<?php

namespace RedJasmine\Product\Application\Attribute\Services\Commands;

use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeStatusEnum;
use RedJasmine\Product\Domain\Attribute\Rules\ProductAttributeNameRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductAttributeGroupCreateCommand extends Data
{
    public string             $name;
    public ?string            $description = null;
    public int                        $sort   = 0;
    public ProductAttributeStatusEnum $status = ProductAttributeStatusEnum::ENABLE;


    public static function attributes(...$args) : array
    {

        return [
            'name'        => __('red-jasmine-product::product-property-group.fields.name'),
            'description' => __('red-jasmine-product::product-property-group.fields.description'),
            'sort'        => __('red-jasmine-product::product-property-group.fields.sort'),
            'status'      => __('red-jasmine-product::product-property-group.fields.status'),
        ];
    }

    public static function rules(ValidationContext $context) : array
    {

        return [
            'name'        => [ 'required', 'max:64', new ProductAttributeNameRule() ],
            'description' => [ 'sometimes', 'nullable', 'string', 'max:255' ],

        ];
    }
}
