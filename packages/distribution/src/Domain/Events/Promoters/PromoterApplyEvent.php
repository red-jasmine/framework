<?php

namespace RedJasmine\Distribution\Domain\Events\Promoters;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * 推广员申请事件
 */
class PromoterApplyEvent
{
    use Dispatchable;

    public function __construct()
    {
    }
}
