<?php

namespace RedJasmine\Distribution\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PromoterBindUserStatusEnum: string
{
    use EnumsHelper;


    case BINDING = 'binding';
    case BOUND = 'bound';
    case UNBOUND = 'unbound';


    /**
     * @return array
     */
    public static function labels() : array
    {
        return [
            self::BINDING->value => '绑定中',
            self::BOUND->value   => '已绑定',
            self::UNBOUND->value => '已解绑',
        ];

    }


    public static function colors() : array
    {
        return [
            self::BOUND->value   => 'success',
            self::BINDING->value => 'gray',
            self::UNBOUND->value => 'danger',
        ];

    }

    public static function icons() : array
    {
        return [
            self::BOUND->value   => 'heroicon-o-link',
            self::BINDING->value => 'heroicon-o-arrow-path-rounded-square',
            self::UNBOUND->value => 'heroicon-o-link-slash',
        ];
    }
}
