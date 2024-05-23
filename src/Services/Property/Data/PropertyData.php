<?php

namespace RedJasmine\Product\Services\Property\Data;

use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Rules\PropertyNameRule;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;


class PropertyData extends Data
{
    public string             $name;
    public int                $sort     = 0;
    public ?int               $group_id = null;
    public PropertyStatusEnum $status   = PropertyStatusEnum::ENABLE;
    public ?array             $extends  = null;

    public static function attributes(...$args) : array
    {

        return [
            'name'     => '属性名',
            'extends'  => '扩展参数',
            'sort'     => '排序',
            'group_id' => '分组',
            'sort'     => '排序值',

        ];
    }

    public static function rules(ValidationContext $context) : array
    {

        return [
            'name'     => [ 'required', 'max:30', new PropertyNameRule() ],
            'extends'  => [ 'sometimes', 'nullable', 'array' ],
            'sort'     => [ 'integer' ],
            'group_id' => [ 'sometimes', 'nullable', 'integer' ],
            'name'     => [ 'required', new PropertyNameRule() ],

        ];
    }
}
