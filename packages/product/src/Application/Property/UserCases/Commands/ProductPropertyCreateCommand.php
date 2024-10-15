<?php

namespace RedJasmine\Product\Application\Property\UserCases\Commands;

use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;
use RedJasmine\Product\Domain\Property\Rules\PropertyNameRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductPropertyCreateCommand extends Data
{

    public string             $name;
    public ?string            $description     = null;
    public ?string            $unit;
    public int                $sort            = 0;
    public int                $groupId         = 0;
    public bool               $isRequired      = false;
    public bool               $isAllowMultiple = false;
    public bool               $isAllowAlias    = false;
    public PropertyTypeEnum   $type            = PropertyTypeEnum::SELECT;
    public PropertyStatusEnum $status          = PropertyStatusEnum::ENABLE;


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
            'name'        => [ 'required', 'max:64', new PropertyNameRule() ],
            'unit'        => [ 'sometimes', 'nullable', 'string', 'max:10', ],
            'sort'        => [ 'integer' ],
            'description' => [ 'sometimes', 'nullable', 'string', 'max:255' ],
            'group_id'    => [ 'sometimes', 'nullable', 'integer' ],

        ];
    }
}
