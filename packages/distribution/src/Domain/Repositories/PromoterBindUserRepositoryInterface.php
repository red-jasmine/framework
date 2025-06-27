<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface PromoterBindUserRepositoryInterface extends RepositoryInterface
{
    /**
     * 查找用户与分销员的绑定关系
     */
    public function findBindRelation(int $promoterId, UserInterface $user) : ?PromoterBindUser;


    /**
     * 获取用户绑定对象
     *
     * @param  UserInterface  $user
     *
     * @return PromoterBindUser|null
     */
    public function findUser(UserInterface $user) : ?PromoterBindUser;


    /**
     * 查询邀请中的状态
     *
     * @param  UserInterface  $user
     *
     * @return PromoterBindUser|null
     */
    public function findUserInviting(UserInterface $user) : ?PromoterBindUser;

    /**
     * 获取用户 生效的 绑定关系
     *
     * @param  UserInterface  $user
     *
     * @return PromoterBindUser|null
     */
    public function findUserBound(UserInterface $user) : ?PromoterBindUser;
} 