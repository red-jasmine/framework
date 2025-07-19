<?php

namespace RedJasmine\Wallet\Domain\Models\ValueObjects;

use RedJasmine\Support\Data\Data;

/**
 * 提现收款方
 */
class Payee extends Data
{

    // 渠道
    public string $channel;
    // 账号
    public string $accountType;

    public string $accountNo;

    // 姓名
    public ?string $name;

    // 证件
    public ?string $certType;

    public ?string $certNo;
}