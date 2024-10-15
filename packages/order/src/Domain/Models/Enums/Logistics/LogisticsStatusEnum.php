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
            self::CREATED->value              => '已创建',
            self::CANCELLED->value            => '已取消',
            self::RECREATED->value            => '重新创建',
            self::CLOSED->value               => '已关闭',
            self::SENDING->value              => '等候发送',
            self::ACCEPTING->value            => '等待接单',
            self::ACCEPTED->value             => '已接单',
            self::REJECTED->value             => '不接单',
            self::PICK_UP->value              => '揽收成功',
            self::PICK_UP_FAILED->value       => '揽收失败',
            self::LOST->value                 => '丢单',
            self::REJECTED_BY_RECEIVER->value => '拒签',
            self::SIGNED->value               => '已签收',
        ];
    }


}
