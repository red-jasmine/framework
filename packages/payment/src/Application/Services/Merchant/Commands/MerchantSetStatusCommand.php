<?php

namespace RedJasmine\Payment\Application\Services\Merchant\Commands;

use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Support\Data\Data;

class MerchantSetStatusCommand extends Data
{


    public int $id;

    public MerchantStatusEnum $status;
}
