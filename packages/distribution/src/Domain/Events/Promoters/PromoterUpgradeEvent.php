<?php

namespace RedJasmine\Distribution\Domain\Events\Promoters;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * 推广员升级事件 降级
 */
class PromoterUpgradeEvent
{
    use Dispatchable;

    public function __construct()
    {
    }
}
