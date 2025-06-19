<?php

namespace RedJasmine\Distribution\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 *  推广员 状态
 */
enum PromoterStatusEnum: string
{
    use EnumsHelper;

        // 申请中
    case APPLYING = 'applying';


    case DISABLE = 'disable';


    case ENABLE = 'enable';

    /**
     * @return array
     */
    public static function labels(): array
    {
        return [
            self::APPLYING->value   => '申请中',
            self::ENABLE->value     => '启用',
            self::DISABLE->value    => '停用',
        ];
    }


    public static function colors(): array
    {
        return [
            self::APPLYING->value   => 'info',
            self::ENABLE->value     =>   'success',
            self::DISABLE->value    =>   'gray',
        ];
    }

    public static function icons(): array
    {
        return [
            self::ENABLE->value  => 'heroicon-o-check-circle',
            self::DISABLE->value => 'heroicon-o-no-symbol',
        ];
    }
}
