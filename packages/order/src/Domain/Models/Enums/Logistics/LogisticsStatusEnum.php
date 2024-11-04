<?php

namespace RedJasmine\Order\Domain\Models\Enums\Logistics;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum LogisticsStatusEnum: string
{
    use EnumsHelper;

    case   CREATED = 'created';
    case   RECREATED = 'recreated';
    case   CANCELLED = 'cancelled';
    case   CLOSED = 'closed';
    case   SENDING = 'sending';
    case   ACCEPTING = 'accepting';
    case   ACCEPTED = 'accepted';
    case   REJECTED = 'rejected';
    case   PICK_UP = 'pick_up';
    case   PICK_UP_FAILED = 'pick_up_failed';
    case   LOST = 'lost';
    case   REJECTED_BY_RECEIVER = 'rejected_by_receiver';
    case   SIGNED = 'signed';

    public static function labels() : array
    {
        return [
            self::CREATED->value              => __('red-jasmine-order::logistics.enums.status.created'),//'已创建',
            self::CANCELLED->value            => __('red-jasmine-order::logistics.enums.shipper.cancelled'),//'已取消',
            self::RECREATED->value            => __('red-jasmine-order::logistics.enums.shipper.recreated'),//'重新创建',
            self::CLOSED->value               => __('red-jasmine-order::logistics.enums.shipper.closed'),//'已关闭',
            self::SENDING->value              => __('red-jasmine-order::logistics.enums.shipper.sending'),//'等候发送',
            self::ACCEPTING->value            => __('red-jasmine-order::logistics.enums.shipper.accepting'),//'等待接单',
            self::ACCEPTED->value             => __('red-jasmine-order::logistics.enums.shipper.accepted'),//'已接单',
            self::REJECTED->value             => __('red-jasmine-order::logistics.enums.shipper.rejected'),//'不接单',
            self::PICK_UP->value              => __('red-jasmine-order::logistics.enums.shipper.pick_up'),//'揽收成功',
            self::PICK_UP_FAILED->value       => __('red-jasmine-order::logistics.enums.shipper.pick_up_failed'),//'揽收失败',
            self::LOST->value                 => __('red-jasmine-order::logistics.enums.shipper.lost'),//'丢单',
            self::REJECTED_BY_RECEIVER->value => __('red-jasmine-order::logistics.enums.shipper.rejected_by_receiver'),//'拒签',
            self::SIGNED->value               => __('red-jasmine-order::logistics.enums.shipper.signed'),//'已签收',
        ];
    }


}
