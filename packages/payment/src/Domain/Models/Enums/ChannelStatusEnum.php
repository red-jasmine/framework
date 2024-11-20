<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ChannelStatusEnum: string
{
    use EnumsHelper;


    case  ENABLE = 'enable';// 启用

    case  DISABLED = 'disabled';// 禁用


    public static function labels() : array
    {
        return [
            self::ENABLE->value   => __('red-jasmine-support::support.enable'),
            self::DISABLED->value => __('red-jasmine-support::support.disabled'),
        ];

    }

}
