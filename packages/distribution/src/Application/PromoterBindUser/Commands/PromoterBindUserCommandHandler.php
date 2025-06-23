<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Commands;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use RedJasmine\Distribution\Application\PromoterBindUser\PromoterBindUserApplicationService;
use RedJasmine\Distribution\Domain\Events\PromoterBindUser\PromoterBindUserEvent;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class PromoterBindUserCommandHandler extends CommandHandler
{
    public function __construct(protected PromoterBindUserApplicationService $service)
    {
        $this->context = new HandleContext();
    }

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(PromoterBindUserCommand $command) : PromoterBindUser
    {
        $this->beginDatabaseTransaction();
        try {
            // 检查用户是否已经绑定了其他分销员
            $existingBind = $this->service->readRepository->findUserActiveBind($command->user);

            if ($existingBind) {
                if ($existingBind->promoter_id === $command->promoterId) {
                    throw new InvalidArgumentException('用户已经绑定该分销员');
                } else {
                    throw new InvalidArgumentException('用户已经绑定了其他分销员');
                }
            }

            // 创建绑定记录
            $bindUser                  = $this->service->newModel();
            $bindUser->promoter_id     = $command->promoterId;
            $bindUser->user_type       = $command->user->getType();
            $bindUser->user_id         = $command->user->getID();
            $bindUser->status          = PromoterBindUserStatusEnum::BOUND;
            $bindUser->bind_time       = Carbon::now();
            $bindUser->protection_time = Carbon::now()->addDays(30); // 30天保护期
            $bindUser->expiration_time = Carbon::now()->addYear(); // 1年有效期
            $bindUser->bind_reason     = $command->bindReason ?? '邀请注册';
            $bindUser->invitation_code = $command->invitationCode;

            $this->service->repository->store($bindUser);

            // 触发绑定事件
            PromoterBindUserEvent::dispatch($bindUser);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $abstractException) {
            $this->rollBackDatabaseTransaction();
            throw $abstractException;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $bindUser;
    }
} 