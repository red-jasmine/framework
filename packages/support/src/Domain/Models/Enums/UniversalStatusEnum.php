<?php

namespace RedJasmine\Support\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum UniversalStatusEnum: string
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
            self::DISABLE->value => '停用',
        ];

    }


    public static function colors() : array
    {
        return [
            self::ENABLE->value  => 'success',
            self::DISABLE->value => 'gray',
        ];

    }

    public static function icons() : array
    {
        return [
            self::ENABLE->value  => 'heroicon-o-check-circle',
            self::DISABLE->value => 'heroicon-o-no-symbol',
        ];
    }
}
