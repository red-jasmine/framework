<?php

namespace RedJasmine\Product\Application\Property\UserCases\Commands;

use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;
use RedJasmine\Product\Domain\Property\Rules\PropertyNameRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductPropertyCreateCommand extends Data
{

    public string             $name;
    public ?string            $unit;
    public int                $sort            = 0;
    public int                $groupId         = 0;
    public bool               $isAllowMultiple = false;
    public PropertyTypeEnum   $type            = PropertyTypeEnum::SELECT;
    public PropertyStatusEnum $status          = PropertyStatusEnum::ENABLE;


    public static function attributes(...$args) : array
    {

        return [
            'name'              => '属性名',
            'type'              => '类型',
            'unit'              => '单位',
            'expands'           => '扩展参数',
            'sort'              => '排序',
            'group_id'          => '分组',
            'is_allow_multiple' => '是否多值',
            'sort'              => '排序值',

        ];
    }

    public static function rules(ValidationContext $context) : array
    {

        return [
            'name'     => ['required', 'max:64', new PropertyNameRule()],
            'unit'     => ['sometimes', 'max:10',],
            'sort'     => ['integer'],
            'expands'  => ['sometimes', 'nullable', 'array'],
            'group_id' => ['sometimes', 'nullable', 'integer'],

        ];
    }
}
