<?php

namespace RedJasmine\Product\Application\Property\UserCases\Commands;

use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Rules\PropertyNameRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductPropertyValueCreateCommand extends Data
{
    public int                $pid;
    public string             $name;
    public ?string            $description = null;
    public int                $sort        = 0;
    public ?int               $groupId     = null;
    public PropertyStatusEnum $status      = PropertyStatusEnum::ENABLE;


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
            'name'        => [ 'required', 'max:64', new PropertyNameRule() ],
            'sort'        => [ 'integer' ],
            'group_id'    => [ 'sometimes', 'nullable', 'integer' ],
            'description' => [ 'sometimes', 'nullable', 'string', 'max:255' ],

        ];
    }
}
