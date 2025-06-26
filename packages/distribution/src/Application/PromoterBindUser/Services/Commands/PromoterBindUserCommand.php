<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Services\Commands;

use RedJasmine\Distribution\Domain\Data\PromoterBindUserData;
use RedJasmine\Support\Contracts\UserInterface;

class PromoterBindUserCommand extends PromoterBindUserData
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
     * 绑定原因/来源
     */
    public ?string $bindReason = null;
} 