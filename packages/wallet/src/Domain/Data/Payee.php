<?php

namespace RedJasmine\Wallet\Domain\Data;

use RedJasmine\Support\Data\Data;

/**
 * 提现收款方
 */
class Payee extends Data
{

    // 渠道
    // 账号

    // 姓名
    // 证件
    public string $channel;

    public string $accountType;

    public string $account;

    public string $name;


}