<?php

namespace RedJasmine\Payment\Application\Services\Merchant\Commands;

use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;

class MerchantSetStatusCommand extends Data
{


    public int $id;

    public MerchantStatusEnum $status;
}
