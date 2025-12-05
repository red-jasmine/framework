<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\MerchantAppStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;

class MerchantAppData extends Data
{

    public int $merchantId;

    public string $name;

    public MerchantAppStatusEnum $status = MerchantAppStatusEnum::ENABLE;


}
