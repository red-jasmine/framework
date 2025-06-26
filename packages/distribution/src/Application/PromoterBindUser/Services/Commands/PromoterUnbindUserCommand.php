<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Services\Commands;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Contracts\UserInterface;

class PromoterUnbindUserCommand extends Data
{
    /**
     * 分销员ID
     */
    public int $promoterId;

    /**
     * 用户
     */
    public UserInterface $user;

    /**
     * 解绑原因
     */
    public ?string $unbindReason = null;
} 