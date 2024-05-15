<?php

namespace RedJasmine\Product\Application\Property\UserCases\Commands;

use Illuminate\Validation\Rule;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Services\Property\Rules\PropertyTitleRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class ProductPropertyValueCreateCommand extends Data
{
    public int                $pid;
    public string             $name;
    public int                $sort       = 0;
    public int                $groupId    = 0;
    public PropertyStatusEnum $status     = PropertyStatusEnum::ENABLE;
    public ?array             $extendInfo = null;

    public static function attributes(...$args) : array
    {

        return [
            'pid'      => '属性ID',
            'name'     => '名称',
            'extends'  => '扩展参数',
            'sort'     => '排序',
            'group_id' => '分组',
            'sort'     => '排序值',

        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'pid'         => [ 'required', 'integer' ],
            'name'        => [ 'required', 'max:30', ],
            'extend_info' => [ 'sometimes', 'array' ],
            'sort'        => [ 'integer' ],
            'group_id'    => [ 'sometimes', 'nullable', 'integer' ],

        ];
    }
}
