<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum StrategyTypeEnum: string
{
    use EnumsHelper;

    case ALLOWED = 'allowed'; // 允许

    case DENIED = 'denied'; // 拒绝


    public static function labels():array
    {
        return [
            self::ALLOWED->value => '允许',
            self::DENIED->value => '拒绝',
        ];

    }

}
