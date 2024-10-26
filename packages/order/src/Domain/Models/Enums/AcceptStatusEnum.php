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
            self::WAIT_ACCEPT->value => '待接单',
            self::ACCEPTED->value    => '已接单',
            self::REJECTED->value    => '已拒单'
        ];

    }

}
