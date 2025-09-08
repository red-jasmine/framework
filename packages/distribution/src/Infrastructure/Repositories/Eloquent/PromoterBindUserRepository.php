<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Distribution\Domain\Repositories\PromoterBindUserRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class PromoterBindUserRepository extends Repository implements PromoterBindUserRepositoryInterface
{
    protected static string $modelClass = PromoterBindUser::class;

    /**
     * 查找用户与分销员的绑定关系
     */
    public function findBindRelation(int $promoterId, UserInterface $user) : ?PromoterBindUser
    {
        /** @var PromoterBindUser $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::query()
                          ->where('promoter_id', $promoterId)
                          ->where('user_type', $user->getType())
                          ->where('user_id', $user->getID())
                          ->first();
    }

    public function findUser(UserInterface $user) : ?PromoterBindUser
    {
        /** @var PromoterBindUser $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::query()
                          ->where('user_type', $user->getType())
                          ->where('user_id', $user->getID())
                          ->first();
    }

    /**
     * 查询你绑定中关系
     *
     * @param  UserInterface  $user
     *
     * @return PromoterBindUser|null
     */
    public function findUserInviting(UserInterface $user) : ?PromoterBindUser
    {
        /** @var PromoterBindUser $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::query()
                          ->where('user_type', $user->getType())
                          ->where('user_id', $user->getID())
                          ->where('status', PromoterBindUserStatusEnum::INVITING)
                          ->first();
    }


    /**
     * 获取用户 生效的 绑定关系
     *
     * @param  UserInterface  $user
     *
     * @return PromoterBindUser|null
     */
    public function findUserBound(UserInterface $user) : ?PromoterBindUser
    {
        /** @var PromoterBindUser $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::query()
                          ->where('user_type', $user->getType())
                          ->where('user_id', $user->getID())
                          ->bound()
                          ->first();
    }


} 