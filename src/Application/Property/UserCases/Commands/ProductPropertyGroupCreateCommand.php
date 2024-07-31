<?php

namespace RedJasmine\Product\Application\Property\UserCases\Commands;

use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Rules\PropertyNameRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductPropertyGroupCreateCommand extends Data
{
    public string             $name;
    public int                $sort    = 0;
    public PropertyStatusEnum $status  = PropertyStatusEnum::ENABLE;


    public static function attributes(...$args) : array
    {

        return [
            'name'    => '名称',
            'expands' => '扩展参数',
            'sort'    => '排序',
            'status'  => '状态',
        ];
    }

    public static function rules(ValidationContext $context) : array
    {

        return [
            'name'    => [ 'required', 'max:64', new PropertyNameRule()],
            'expands' => [ 'sometimes', 'array' ],

        ];
    }
}
