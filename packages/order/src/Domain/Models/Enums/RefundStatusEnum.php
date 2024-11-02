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
    case  REFUND_SUCCESS = 'refund_success';
    case  REFUND_CANCEL = 'cancel';

    public static function labels() : array
    {
        return [

            self::WAIT_SELLER_AGREE->value        => '等待卖家同意退款',
            self::WAIT_SELLER_AGREE_RETURN->value => '等待卖家同意退货',
            self::WAIT_BUYER_RETURN_GOODS->value  => '等待买家退货',
            self::WAIT_SELLER_CONFIRM->value      => '等待卖家确认',
            self::WAIT_SELLER_RESHIPMENT->value   => '等待卖家发货',
            self::SELLER_REJECT_BUYER->value      => '卖家拒绝',
            self::REFUND_SUCCESS->value           => '退款成功',
            self::REFUND_CANCEL->value            => '退款取消',
        ];
    }
}
