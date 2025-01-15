<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\AccountTypeEnum;
use RedJasmine\Support\Data\Data;

class SettleAccount extends Data
{

    public string          $channelCode;
    public string          $channelMerchantId;
    public AccountTypeEnum $settleAccountType;
    public string          $settleAccount;


}
