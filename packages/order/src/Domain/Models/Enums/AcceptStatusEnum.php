<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 接单状态
 */
enum AcceptStatusEnum: string
{
    use EnumsHelper;

    case ACCEPTING = 'accepting';

    case ACCEPTED = 'accepted';

    case REJECTED = 'rejected';


    public static function labels() : array
    {
        return [
            self::ACCEPTING->value => __('red-jasmine-order::order.enums.accept_status.accepting'),
            self::ACCEPTED->value  => __('red-jasmine-order::order.enums.accept_status.accepted'),
            self::REJECTED->value  => __('red-jasmine-order::order.enums.accept_status.rejected'),
        ];

    }


    public static function icons() : array
    {
        return [
            self::ACCEPTING->value => 'heroicon-o-clock',
            self::ACCEPTED->value  => 'heroicon-o-check-badge',
            self::REJECTED->value  => 'heroicon-o-no-symbol',


        ];
    }

    public static function colors() : array
    {
        return [

            self::REJECTED->value  => 'danger',
            self::ACCEPTING->value => 'primary',
            self::ACCEPTED->value  => 'success',


        ];
    }

}
