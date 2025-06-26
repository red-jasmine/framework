<?php

namespace RedJasmine\Invitation\Application\Services\Commands;

use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
use RedJasmine\Invitation\Domain\Data\UseInvitationCodeData;
use RedJasmine\Invitation\Domain\Models\InvitationRecord;
use RedJasmine\Invitation\Exceptions\InvitationException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * 使用邀请码命令处理器
 */
class InvitationCodeUseCommandHandler extends CommandHandler
{
    public function __construct(
        protected InvitationCodeApplicationService $service
    ) {
        $this->context = new HandleContext();
    }

    /**
     * @param  UseInvitationCodeData  $command
     *
     * @return InvitationRecord
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(UseInvitationCodeData $command) : InvitationRecord
    {
        $this->context->setCommand($command);

        $this->beginDatabaseTransaction();

        try {
            // 验证命令
            $this->service->hook('use.validate', $this->context, fn() => $this->validate($this->context));

            // 查找邀请码
            $invitationCode = $this->service->findByCode($command->code);
            if (!$invitationCode) {
                throw new InvitationException('邀请码不存在', InvitationException::CODE_NOT_FOUND);
            }

            // 使用邀请码
            $record = $invitationCode->use(
                $command->invitee,
                $command->context,
                $command->targetUrl,
                $command->targetType
            );

            $this->service->repository->update($invitationCode);
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $record;
    }

    /**
     * 验证命令
     */
    protected function validate(HandleContext $context) : void
    {
        /** @var UseInvitationCodeData $command */
        $command = $context->getCommand();

        // 验证邀请码
        if (empty($command->code)) {
            throw new InvitationException('邀请码不能为空');
        }

        // 验证被邀请人
        if (!$command->invitee) {
            throw new InvitationException('被邀请人不能为空');
        }

        // 可以在这里添加其他业务验证
        // 例如：检查用户是否已经使用过邀请码、检查使用频率限制等
    }
} 