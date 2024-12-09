<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\PermissionStatusEnum;
use RedJasmine\Support\Data\Data;

class MerchantChannelAppPermissionData extends Data
{

    public int $merchantId;

    public int $channelAppId;

    public PermissionStatusEnum $status = PermissionStatusEnum::ENABLE;

}
