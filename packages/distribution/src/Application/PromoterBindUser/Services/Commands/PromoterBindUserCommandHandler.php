<?php

namespace RedJasmine\Distribution\Application\PromoterBindUser\Services\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use RedJasmine\Distribution\Application\PromoterBindUser\Services\PromoterBindUserApplicationService;
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
            // 查询用户绑定信息
            $bindUser = $this->service->repository->findUser($command->user) ?? $this->service->newModel();

            // TODO 业务逻辑收敛到领域层
            // 创建绑定记录
            $bindTime       = Carbon::now();
            $protectionTime = $bindTime->clone()->addDays(Config::get('red-jasmine-distribution.user_bind_mode.protection_day', 30));
            $expirationTime = $bindTime->clone()->addDays(Config::get('red-jasmine-distribution.user_bind_mode.expiration_day', 365));


            $bindUser->promoter_id = $command->promoterId;
            $bindUser->user        = $command->user;
            $bindUser->status      = PromoterBindUserStatusEnum::BOUND;
            // TODO 根据配置获取 保护期 和 有效期
            $bindUser->bind_time       = $bindTime;
            $bindUser->protection_time = $protectionTime;
            $bindUser->expiration_time = $expirationTime;

            $this->service->repository->store($bindUser);

            // 触发绑定事件
            // PromoterBindUserEvent::dispatch($bindUser);

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