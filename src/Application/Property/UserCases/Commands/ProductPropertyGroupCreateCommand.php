<?php

namespace RedJasmine\Product\Application\Property\UserCases\Commands;

use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Rules\PropertyNameRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductPropertyGroupCreateCommand extends Data
{
    public string             $name;
    public ?string            $description = null;
    public int                $sort        = 0;
    public PropertyStatusEnum $status      = PropertyStatusEnum::ENABLE;


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
            'name'        => [ 'required', 'max:64', new PropertyNameRule() ],
            'description' => [ 'sometimes', 'nullable', 'string', 'max:255' ],

        ];
    }
}
