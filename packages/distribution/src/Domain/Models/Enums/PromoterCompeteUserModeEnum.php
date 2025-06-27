<?php

namespace RedJasmine\Distribution\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PromoterCompeteUserModeEnum: string
{
    use EnumsHelper;

    case CONTACT = 'contact';
    case ORDER = 'order';

    /**
     * @return array
     */
    public static function labels() : array
    {
        return [
            self::CONTACT->value => '触达',
            self::ORDER->value   => '下单',
        ];

    }


    public static function colors() : array
    {
        return [
            self::CONTACT->value => 'gray',
            self::ORDER->value   => 'danger',

        ];

    }

    public static function icons() : array
    {
        return [
            self::CONTACT->value => 'heroicon-o-link',
            self::ORDER->value   => 'heroicon-o-link-slash',
        ];
    }
}
