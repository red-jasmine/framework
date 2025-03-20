<?php

namespace RedJasmine\Wallet\Domain\Models\Enums\Withdrawals;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 付款状态
 */
enum WithdrawalPaymentStatusEnum: string
{

    use EnumsHelper;

    case PREPARE = 'prepare';
    case PAYING = 'paying';
    case SUCCESS = 'success';
    case FAIL = 'fail';

    public static function labels() : array
    {

        return [
            self::PREPARE->value => '准备',
            self::PAYING->value  => '处理中',
            self::SUCCESS->value => '成功',
            self::FAIL->value    => '失败',
        ];
    }

}