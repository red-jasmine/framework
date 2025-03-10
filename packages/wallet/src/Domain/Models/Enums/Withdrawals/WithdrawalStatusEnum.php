<?php

namespace RedJasmine\Wallet\Domain\Models\Enums\Withdrawals;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum WithdrawalStatusEnum: string
{

    use EnumsHelper;

    case PROCESSING = 'processing';
    case SUCCESS = 'success';
    case FAIL = 'fail';

    public static function labels() : array
    {

        return [
            self::PROCESSING->value => '处理中',
            self::SUCCESS->value    => '成功',
            self::FAIL->value       => '失败',
        ];
    }

}
