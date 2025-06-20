<?php

namespace RedJasmine\Distribution\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 审核状态
 */
enum PromoterApplyAuditStatusEnum: string
{
    use EnumsHelper;

    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELED = 'canceled';
    case FAILED = 'failed';
    case EXPIRED = 'expired';


    public static function labels() : array
    {
        return [
            self::PENDING->value  => '待审核',
            self::APPROVED->value => '审核通过',
            self::REJECTED->value => '审核拒绝',
            self::CANCELED->value => '取消申请',
            self::FAILED->value   => '申请失败',
            self::EXPIRED->value  => '申请过期',

        ];
    }
}
