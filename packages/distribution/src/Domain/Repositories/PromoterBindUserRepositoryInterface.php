<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 推广员绑定用户仓库接口
 *
 * 提供推广员绑定用户实体的读写操作统一接口
 *
 * @method PromoterBindUser find($id)
 */
interface PromoterBindUserRepositoryInterface extends RepositoryInterface
{
    /**
     * 查找用户与分销员的绑定关系
     */
    public function findBindRelation(int $promoterId, UserInterface $user) : ?PromoterBindUser;

    /**
     * 获取用户绑定对象
     */
    public function findUser(UserInterface $user) : ?PromoterBindUser;

    /**
     * 查询邀请中的状态
     */
    public function findUserInviting(UserInterface $user) : ?PromoterBindUser;

    /**
     * 获取用户 生效的 绑定关系
     */
    public function findUserBound(UserInterface $user) : ?PromoterBindUser;

    /**
     * 查找用户与分销员的有效绑定关系
     */
    public function findActiveBind(int $promoterId, UserInterface $user) : ?PromoterBindUser;

    /**
     * 查找用户的当前有效绑定关系（不指定分销员）
     */
    public function findUserActiveBind(UserInterface $user) : ?PromoterBindUser;
}
