<?php

namespace RedJasmine\Product\Domain\Property\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PropertyStatusEnum: string
{
    use EnumsHelper;

    case  ENABLE = 'enable';  // 启用
    case  DISABLE = 'disable';  // 禁用


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

}
