<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Services\Commands;

use RedJasmine\Distribution\Application\PromoterBindUser\PromoterBindUserApplicationService;
use RedJasmine\Distribution\Domain\Events\PromoterBindUser\PromoterUnbindUserEvent;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class PromoterUnbindUserCommandHandler extends CommandHandler
{
    public function __construct(protected PromoterBindUserApplicationService $service)
    {
        $this->context = new HandleContext();
    }

    /**
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(PromoterUnbindUserCommand $command): bool
    {
        $this->beginDatabaseTransaction();
        try {
            // 查找绑定记录
            $bindUser = $this->service->repository->findActiveBind($command->promoterId, $command->user);

            if (!$bindUser) {
                throw new \InvalidArgumentException('未找到绑定记录');
            }

            // 检查保护期
            if ($bindUser->protection_time && $bindUser->protection_time->isFuture()) {
                throw new \InvalidArgumentException('绑定关系在保护期内，无法解绑');
            }

            // 更新状态为解绑
            $bindUser->status = PromoterBindUserStatusEnum::UNBOUND;
            $bindUser->unbind_reason = $command->unbindReason ?? '主动解绑';
            $bindUser->unbind_time = now();

            $this->service->repository->update($bindUser);

            // 触发解绑事件
            PromoterUnbindUserEvent::dispatch($bindUser);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $abstractException) {
            $this->rollBackDatabaseTransaction();
            throw $abstractException;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return true;
    }
}
