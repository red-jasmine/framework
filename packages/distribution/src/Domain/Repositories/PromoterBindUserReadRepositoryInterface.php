<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface PromoterBindUserReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 查找用户与分销员的绑定关系
     */
    public function findBindRelation(int $promoterId, UserInterface $user): ?PromoterBindUser;
    
    /**
     * 查找用户与分销员的有效绑定关系
     */
    public function findActiveBind(int $promoterId, UserInterface $user): ?PromoterBindUser;
    
    /**
     * 查找用户的当前有效绑定关系（不指定分销员）
     */
    public function findUserActiveBind(UserInterface $user): ?PromoterBindUser;
} 