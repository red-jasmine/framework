<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Commands;

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

    /**
     * 邀请码
     */
    public ?string $invitationCode = null;
} 