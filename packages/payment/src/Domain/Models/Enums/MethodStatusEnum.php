<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum MethodStatusEnum: string
{
    use EnumsHelper;


    case  ENABLE = 'enable';// 启用

    case  DISABLE = 'disable';// 停用


    public static function labels() : array
    {
        return [
            self::ENABLE->value   => __('red-jasmine-support::support.enable'),
            self::DISABLE->value => __('red-jasmine-support::support.disable'),
        ];

    }

}
