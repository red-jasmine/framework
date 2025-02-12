<?php

namespace RedJasmine\ResourceUsage\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 *
 * 资源使用模式
 *
 */
enum ResourceUsageModeEnum: string
{
    use EnumsHelper;

    case  CONSUME = 'consume';// 消耗

    case  SETTLE = 'settle';// 结算模式


    public static function labels() : array
    {
        return [
            self::CONSUME->value => '消费模式',
            self::SETTLE->value  => '结算模式',
        ];

    }
}
