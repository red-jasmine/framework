<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum SettlementStatusEnum: string
{
    use EnumsHelper;

    case NONE = 'none';
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case PARTIAL = 'partial';
    case COMPLETED = 'completed';


    public static function labels() : array
    {
        return [
            self::NONE->value       => '无结算',
            self::PENDING->value    => '等结算',
            self::PROCESSING->value => '结算中',
            self::PARTIAL->value    => '部分结算',
            self::COMPLETED->value  => '已结算',
        ];
    }

}
