<?php

namespace RedJasmine\Wallet\DataTransferObjects\Withdrawals;

use RedJasmine\Support\DataTransferObjects\Data;

class WalletWithdrawalDTO extends Data
{

    /**
     * 金额
     * @var string|int|float
     */
    public string|int|float $amount;

    /**
     * 手续费
     * @var string|int|float
     */
    public string|int|float $fee = 0;

    /**
     * 提现类型
     * @var string
     */
    public string $transferType;
    /**
     * 提现账户
     * @var string
     */
    public string $transferAccount;
    /**
     * 账户实名
     * @var ?string
     */
    public ?string $transferAccountRealName = null;


}
