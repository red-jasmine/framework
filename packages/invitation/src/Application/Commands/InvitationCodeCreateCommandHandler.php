<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Application\Commands;

use RedJasmine\Invitation\Application\Data\InvitationCodeCreateCommand;
use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
use RedJasmine\Invitation\Domain\Data\InvitationCodeData;
use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Services\InvitationCodeService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * 邀请码创建命令处理器
 */
final class InvitationCodeCreateCommandHandler extends CommandHandler
{
    public function __construct(
        protected InvitationCodeApplicationService $service
    ) {
        $this->context = new HandleContext();
    }

    /**
     * 处理邀请码创建命令
     * 
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(InvitationCodeCreateCommand $command): InvitationCode
    {
        $this->beginDatabaseTransaction();
        
        try {
            // 构建邀请人值对象
            $inviter = new \RedJasmine\Invitation\Domain\Models\ValueObjects\Inviter(
                $command->inviterType,
                $command->inviterId,
                $command->inviterName
            );

            // 转换为领域数据对象
            $invitationCodeData = InvitationCodeData::from([
                'inviter' => $inviter,
                'code' => $command->code,
                'generateType' => $command->generateType,
                'expiredAt' => $command->expiresAt ? new \DateTime($command->expiresAt) : null,
                'maxUsages' => $command->maxUsage,
                'tags' => $command->tags,
                'remarks' => null, // 从extraData中获取或默认为null
            ]);

            // 使用领域服务创建邀请码
            $domainService = new InvitationCodeService(
                $this->service->repository,
                app(\RedJasmine\Invitation\Infrastructure\Services\InvitationCodeGenerator::class)
            );
            
            $invitationCode = $domainService->generateCode($invitationCodeData);
            
            // 保存邀请码
            $this->service->repository->store($invitationCode);

            $this->commitDatabaseTransaction();
            
            return $invitationCode;
            
        } catch (AbstractException $abstractException) {
            $this->rollBackDatabaseTransaction();
            throw $abstractException;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
} 