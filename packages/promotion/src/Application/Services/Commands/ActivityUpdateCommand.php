<?php

namespace RedJasmine\Promotion\Application\Services\Commands;

use RedJasmine\Promotion\Domain\Data\ActivityData;

/**
 * 更新活动命令
 */
class ActivityUpdateCommand extends ActivityData
{
    public int $id;
}
