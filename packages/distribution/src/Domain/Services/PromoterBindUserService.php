<?php

namespace RedJasmine\Distribution\Domain\Services;

use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterCompeteUserModeEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterUnboundUserTypeEnum;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Distribution\Domain\Repositories\PromoterBindUserRepositoryInterface;
use RedJasmine\Distribution\Exceptions\PromoterBindUserException;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Service\Service;

class PromoterBindUserService extends Service
{

    public function __construct(
        protected PromoterBindUserRepositoryInterface $repository,
    ) {
    }


    /**
     * 处理邀请中的状态
     *
     * @param  Promoter  $promoter
     * @param  UserInterface  $user
     * @param  PromoterBindUser  $userInviting
     *
     * @return true
     * @throws PromoterBindUserException
     */
    protected function hasInviting(Promoter $promoter, UserInterface $user, PromoterBindUser $userInviting) : true
    {
        // 如果是当前分销员 ，进行再次绑定
        if ($userInviting->isBelongsToPromoter($promoter)) {
            $userInviting->activation();

            return false;
        }

        // 如果过期了
        if ($userInviting->isExpired()) {
            $userInviting->setUnbound();
            return true;
        }
        throw new PromoterBindUserException('当前用户锁定中');
    }

    /**
     * 需要处理的关系
     *
     * @param  Promoter  $promoter
     * @param  UserInterface  $user
     *
     * @return PromoterBindUser[]
     * @throws PromoterBindUserException
     */
    public function bind(Promoter $promoter, UserInterface $user) : array
    {
        // 需要处理的关系
        $binds = [];
        // 1.当前用户存在 绑定中 关系、未过期、在保护 （排除 当前 分销员）
        $userInviting = $this->repository->findUserInviting($user);
        if ($userInviting) {
            // 处理绑定总关系
            if ($this->hasInviting($promoter, $user, $userInviting) === false) {
                $binds[] = $userInviting;
                return $binds;
            }
        }

        // 查询当前绑定的关系
        $userBound = $this->repository->findUserBound($user);
        // 构建或者查询 用户和当前分销员的绑定关系
        $promoterBindUser = ($userBound && $userBound->isBelongsToPromoter($promoter))
            ? $userBound : $this->getUserBind($promoter, $user);

        // 当前用户未绑定
        if (!$userBound) {
            // 直接进行绑定
            $promoterBindUser->status = PromoterBindUserStatusEnum::BOUND;
            $promoterBindUser->setBound($promoter, $user);
            $binds[] = $promoterBindUser;
            return $binds;
        }

        //|----------------------------------------
        // 已绑定关系的用户

        // 判断已经绑定的关系是否为当前 分销员
        if ($userBound->isBelongsToPromoter($promoter)) {
            // 激活处理
            $userBound->activation();
            $binds[] = $userBound;
            return $binds;
        }
        //|----------------------------------------
        // 已绑定关系不是当前分销员

        // 当前用户已绑定、已过期
        // 则 直接绑定关系
        if ($userBound->isExpired()) {
            // 设置为解绑
            $userBound->setUnbound();
            // 设置新的绑定关系
            $promoterBindUser->setBound($promoterBindUser, $user);

            $binds[] = $userBound;
            $binds[] = $promoterBindUser;
            return $binds;

        }
        // 当前用户已绑定、未过期、超过保护期间
        //  则 和当前 分销员的关系 为 邀请中
        if ($userBound->isOverProtection()) {
            $promoterBindUser->status = PromoterBindUserStatusEnum::INVITING;
            // 进行抢客竞争处理
            $this->competeUser($userBound, $promoterBindUser, $user, $promoter);
            $binds[] = $userBound;
            $binds[] = $promoterBindUser;
            return $binds;
        }
        // 当前用户已绑定、生效、在保护期
        if (!$userBound->isExpired() && $userBound->isOverProtection()) {
            throw new PromoterBindUserException('用户已绑定，不能重复绑定');
        }
        return $binds;

    }

    protected function getUserBind(Promoter $promoter, UserInterface $user) : PromoterBindUser
    {
        return $this->repository->findBindRelation($promoter->id, $user) ?? PromoterBindUser::make();
    }


    /**
     * 抢客
     *
     * @param  PromoterBindUser  $oldPromoterBindUser
     * @param  PromoterBindUser  $newPromoterBindUser
     * @param  UserInterface  $user
     * @param  Promoter  $promoter
     *
     * @return void
     */
    protected function competeUser(
        PromoterBindUser $oldPromoterBindUser,
        PromoterBindUser $newPromoterBindUser,
        UserInterface $user,
        Promoter $promoter
    ) : void {

        $getCompeteUserMode = app(DistributionConfigService::class)->getCompeteUserMode();
        // 根据 配置的抢客模式，进行抢客
        if ($getCompeteUserMode === PromoterCompeteUserModeEnum::ORDER) {
            // 设置为邀请关系
            $newPromoterBindUser->setInviting($promoter, $user);
        }

        if ($getCompeteUserMode === PromoterCompeteUserModeEnum::CONTACT) {
            // 解绑老的关系
            $oldPromoterBindUser->setUnbound(PromoterUnboundUserTypeEnum::COMPETE);
            // 设置为邀请关系
            $newPromoterBindUser->setBound($user, $promoter);

        }


    }
}