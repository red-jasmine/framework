<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 退款状态
 */
enum RefundStatusEnum: string
{

    use EnumsHelper;


    case  PENDING = 'pending'; // 待处理
    case  RETURNING = 'returning';
    case  SHIPPING = 'shipping';
    case  CHECKING = 'checking';
    case  REJECTED = 'rejected';
    case  FINISHED = 'finished';
    case  CANCEL = 'cancel';
    case  CLOSED = 'closed';

    public static function labels() : array
    {
        return [

            self::PENDING->value   => __('red-jasmine-order::refund.enums.refund_status.pending'),
            self::RETURNING->value => __('red-jasmine-order::refund.enums.refund_status.returning'),
            self::CHECKING->value  => __('red-jasmine-order::refund.enums.refund_status.checking'),
            self::SHIPPING->value  => __('red-jasmine-order::refund.enums.refund_status.shipping'),
            self::REJECTED->value  => __('red-jasmine-order::refund.enums.refund_status.rejected'),
            self::FINISHED->value  => __('red-jasmine-order::refund.enums.refund_status.finished'),
            self::CANCEL->value    => __('red-jasmine-order::refund.enums.refund_status.cancel'),
            self::CLOSED->value    => __('red-jasmine-order::refund.enums.refund_status.closed'),
        ];
    }
}
