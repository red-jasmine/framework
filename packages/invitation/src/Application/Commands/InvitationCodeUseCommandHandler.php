<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Application\Commands;

use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
use RedJasmine\Invitation\Domain\Models\InvitationUsageLog;
use RedJasmine\Invitation\Domain\Services\InvitationCodeService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * 邀请码使用命令处理器
 */
final class InvitationCodeUseCommandHandler extends CommandHandler
{
    public function __construct(
        protected InvitationCodeApplicationService $service
    ) {
        $this->context = new HandleContext();
    }

    /**
     * 处理邀请码使用命令
     * 
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(InvitationCodeUseCommand $command): InvitationUsageLog
    {
        $this->beginDatabaseTransaction();
        
        try {
            // 使用领域服务处理邀请码使用
            $domainService = new InvitationCodeService(
                $this->service->repository,
                app(\RedJasmine\Invitation\Infrastructure\Services\InvitationCodeGenerator::class)
            );
            
            $usageLog = $domainService->useCode(
                $command->code,
                $command->user,
                $command->context
            );

            $this->commitDatabaseTransaction();
            
            return $usageLog;
            
        } catch (AbstractException $abstractException) {
            $this->rollBackDatabaseTransaction();
            throw $abstractException;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
} 