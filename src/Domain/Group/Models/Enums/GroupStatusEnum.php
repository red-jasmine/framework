<?php

namespace RedJasmine\Product\Domain\Group\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum GroupStatusEnum: string
{

    use EnumsHelper;

    case DISABLE = 'disable';


    case ENABLE = 'enable';

    /**
     * @return array
     */
    public static function labels() : array
    {
        return [
            self::ENABLE->value  => '启用',
            self::DISABLE->value => '禁用',
        ];

    }


    public static function colors():array
    {
        return [
            self::ENABLE->value  => 'success',
            self::DISABLE->value => 'gray',
        ];

    }
}
