<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 退款状态
 */
enum RefundStatusEnum: string
{

    use EnumsHelper;


    case  WAIT_SELLER_AGREE = 'wait_seller_agree';
    case  WAIT_SELLER_AGREE_RETURN = 'wait_seller_agree_return';
    case  WAIT_BUYER_RETURN_GOODS = 'wait_buyer_return_goods';
    case  WAIT_SELLER_RESHIPMENT = 'wait_seller_reshipment';
    case  WAIT_SELLER_CONFIRM = 'wait_seller_confirm';
    case  SELLER_REJECT_BUYER = 'seller_reject_buyer';
    case  FINISHED = 'finished';
    case  CANCEL = 'cancel';
    case  CLOSED = 'closed';

    public static function labels() : array
    {
        return [

            self::WAIT_SELLER_AGREE->value        => __('red-jasmine-order::refund.enums.refund_status.wait_seller_agree'),
            self::WAIT_SELLER_AGREE_RETURN->value => __('red-jasmine-order::refund.enums.refund_status.wait_seller_agree_return'),
            self::WAIT_BUYER_RETURN_GOODS->value  => __('red-jasmine-order::refund.enums.refund_status.wait_buyer_return_goods'),
            self::WAIT_SELLER_CONFIRM->value      => __('red-jasmine-order::refund.enums.refund_status.wait_seller_confirm'),
            self::WAIT_SELLER_RESHIPMENT->value   => __('red-jasmine-order::refund.enums.refund_status.wait_seller_reshipment'),
            self::SELLER_REJECT_BUYER->value      => __('red-jasmine-order::refund.enums.refund_status.seller_reject_buyer'),
            self::FINISHED->value                 => __('red-jasmine-order::refund.enums.refund_status.finished'),
            self::CANCEL->value                   => __('red-jasmine-order::refund.enums.refund_status.cancel'),
            self::CLOSED->value                   => __('red-jasmine-order::refund.enums.refund_status.closed'),
        ];
    }
}
