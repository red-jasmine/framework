<?php

namespace RedJasmine\Distribution\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PromoterBindUserStatusEnum: string
{
    use EnumsHelper;


    case INVITING = 'inviting';
    case BOUND = 'bound';
    case UNBOUND = 'unbound';


    /**
     * @return array
     */
    public static function labels() : array
    {
        return [
            self::INVITING->value => '邀请中',
            self::BOUND->value    => '已绑定',
            self::UNBOUND->value  => '已解绑',
        ];

    }


    public static function colors() : array
    {
        return [
            self::BOUND->value    => 'success',
            self::INVITING->value => 'gray',
            self::UNBOUND->value  => 'danger',
        ];

    }

    public static function icons() : array
    {
        return [
            self::BOUND->value    => 'heroicon-o-link',
            self::INVITING->value => 'heroicon-o-arrow-path-rounded-square',
            self::UNBOUND->value  => 'heroicon-o-link-slash',
        ];
    }
}
