<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 接单状态
 */
enum AcceptStatusEnum: string
{
    use EnumsHelper;

    case WAIT_ACCEPT = 'wait_accept';

    case ACCEPTED = 'accepted';

    case REJECTED = 'rejected';


    public static function labels() : array
    {
        return [
            self::WAIT_ACCEPT->value => __('red-jasmine-order::order.enums.accept_status.wait_accept'),
            self::ACCEPTED->value    => __('red-jasmine-order::order.enums.accept_status.accepted'),
            self::REJECTED->value    => __('red-jasmine-order::order.enums.accept_status.rejected'),
        ];

    }


    public static function icons() : array
    {
        return [
            self::WAIT_ACCEPT->value => 'heroicon-o-clock',
            self::ACCEPTED->value    => 'heroicon-o-check-badge',
            self::REJECTED->value    => 'heroicon-o-no-symbol',


        ];
    }

    public static function colors() : array
    {
        return [

            self::REJECTED->value    => 'danger',
            self::WAIT_ACCEPT->value => 'primary',
            self::ACCEPTED->value    => 'success',


        ];
    }

}
