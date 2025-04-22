<?php

namespace RedJasmine\Address\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum AddressStatusEnum: string
{

    use EnumsHelper;

    case DISABLE = 'disable';

    case ENABLE = 'enable';

    public static function labels() : array
    {
        return [
            self::DISABLE->value => __('red-jasmine-support::support.enable'),
            self::DISABLE->value => __('red-jasmine-support::support.disable'),
        ];
    }

}
