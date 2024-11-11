<?php

namespace RedJasmine\Order\Domain\Models\Features;

use RedJasmine\Order\Domain\Models\Enums\TradePartyEnums;

trait HasRemarks
{
    public function remarks(TradePartyEnums $tradeParty, string $remarks = null, bool $isAppend = false) : void
    {
        // 根据交易双方类型动态确定备注信息字段名
        $field = $tradeParty->value . '_remarks';

        $model = $this;
        // 在确定的对象上添加或更新备注信息
        if ($isAppend && blank($model->info->{$field})) {
            $isAppend = false;
        }
        if ($isAppend) {
            $model->info->{$field} .= "\n\r" . $remarks;
        } else {
            $model->info->{$field} = $remarks;
        }

    }
}
