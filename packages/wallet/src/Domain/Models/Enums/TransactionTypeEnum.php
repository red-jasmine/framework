<?php

namespace RedJasmine\Wallet\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum TransactionTypeEnum: string
{
    use EnumsHelper;

    case RECHARGE = 'recharge'; // 充值
    case WITHDRAWAL = 'withdrawal'; // 提现

    case PAYMENT = 'payment'; // 支付
    case REFUND = 'refund'; // 退款
    case FROZEN = 'frozen'; // 冻结
    case UNFROZEN = 'unfrozen'; // 解冻
    case TRANSFER = 'transfer'; // 转账
    case RECEIVE = 'receive'; // 收款


    public static function labels() : array
    {

        return [
            self::RECHARGE->value   => '充值',
            self::WITHDRAWAL->value => '提现',
            self::PAYMENT->value    => '支付',
            self::REFUND->value     => '退款',
            self::FROZEN->value     => '冻结',
            self::UNFROZEN->value   => '解冻',
            self::TRANSFER->value   => '转账',
            self::RECEIVE->value    => '收账',
        ];
    }


}
