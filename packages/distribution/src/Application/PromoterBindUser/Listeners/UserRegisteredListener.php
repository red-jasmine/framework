<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Listeners;

use Illuminate\Support\Facades\Log;
use RedJasmine\Distribution\Application\Promoter\Services\PromoterApplicationService;
use RedJasmine\Distribution\Application\Promoter\Services\Queries\FindByOwnerQuery;
use RedJasmine\Distribution\Application\PromoterBindUser\Services\Commands\PromoterBindUserCommand;
use RedJasmine\Distribution\Application\PromoterBindUser\Services\PromoterBindUserApplicationService;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\User\Domain\Events\UserRegisteredEvent;
use Throwable;

class UserRegisteredListener
{
    public function __construct(
        protected PromoterApplicationService $promoterApplicationService,
        protected PromoterBindUserApplicationService $promoterBindUserApplicationService,


    ) {
    }

    public function handle(UserRegisteredEvent $event) : void
    {

        $user = $event->user;
        if (!$user->inviter) {
            return;
        }

        try {

            // 如果存在邀请人
            // 查询是否已经是分销员
            // 如果是分销员，那么就进行绑定关系
            $findByOwnerQuery = FindByOwnerQuery::from([
                'owner' => $user->inviter
            ]);
            $promoter         = $this->promoterApplicationService->findByOwner($findByOwnerQuery);

            $promoterBindUserCommand             = new PromoterBindUserCommand;
            $promoterBindUserCommand->promoterId = $promoter->id;
            $promoterBindUserCommand->user       = $user;

            $this->promoterBindUserApplicationService->bind($promoterBindUserCommand);
        } catch (AbstractException $abstractException) {
            Log::info($abstractException->getMessage());
        } catch (Throwable $throwable) {
            throw $throwable;
            report($throwable);
        }
    }
}
