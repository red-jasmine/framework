<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\PermissionStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;

class MerchantChannelAppPermissionData extends Data
{

    public int $merchantAppId;

    public int $channelAppId;

    public PermissionStatusEnum $status = PermissionStatusEnum::ENABLE;

}
