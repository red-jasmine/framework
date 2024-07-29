<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum SettlementStatusEnum: string
{
    use EnumsHelper;

    case NIL = 'nil';
    case WAIT_SETTLEMENT = 'wait_settlement';
    case IN_SETTLEMENT = 'in_settlement';
    case PART_SETTLEMENT = 'part_settlement';
    case COMPLETE_SETTLEMENT = 'complete_settlement';


    public static function labels() : array
    {
        return [
            self::NIL->value         => '',
            self::WAIT_SETTLEMENT->value     => '等待结算',
            self::IN_SETTLEMENT->value       => '结算中',
            self::PART_SETTLEMENT->value     => '部分结算',
            self::COMPLETE_SETTLEMENT->value => '完成结算',
        ];
    }

}
