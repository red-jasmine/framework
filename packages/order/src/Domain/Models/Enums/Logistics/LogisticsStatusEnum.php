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

    case   ACCEPTING = 'accepting';
    case   ACCEPTED = 'accepted';
    case   REJECTED = 'rejected';

    case   LOST = 'lost';

    case   COLLECT = 'collect';
    case   SENDING = 'sending';
    case   DISPATCH = 'dispatch';
    case   REJECTED_SIGN = 'rejected_sign';
    case   SIGNED = 'signed';

    public static function labels() : array
    {
        return [
            self::CREATED->value   => __('red-jasmine-order::logistics.enums.status.created'),//'已创建',
            self::CANCELLED->value => __('red-jasmine-order::logistics.enums.status.cancelled'),//'已取消',
            self::RECREATED->value => __('red-jasmine-order::logistics.enums.status.recreated'),//'重新创建',
            self::CLOSED->value    => __('red-jasmine-order::logistics.enums.status.closed'),//'已关闭',
            self::LOST->value      => __('red-jasmine-order::logistics.enums.status.lost'),//'丢单',

            self::ACCEPTING->value => __('red-jasmine-order::logistics.enums.status.accepting'),//'等待接单',
            self::ACCEPTED->value  => __('red-jasmine-order::logistics.enums.status.accepted'),//'已接单',
            self::REJECTED->value  => __('red-jasmine-order::logistics.enums.status.rejected'),//'不接单',


            self::COLLECT->value       => __('red-jasmine-order::logistics.enums.status.collect'),//'揽收 成功',
            self::SENDING->value       => __('red-jasmine-order::logistics.enums.status.sending'),//'揽收 成功',
            self::DISPATCH->value       => __('red-jasmine-order::logistics.enums.status.dispatch'),//'揽收 成功',
            self::SENDING->value       => __('red-jasmine-order::logistics.enums.status.sending'),//'等候发送',
            self::REJECTED_SIGN->value => __('red-jasmine-order::logistics.enums.status.rejected_sign'),//'拒签',
            self::SIGNED->value        => __('red-jasmine-order::logistics.enums.status.signed'),//'已签收',
        ];
    }


}
