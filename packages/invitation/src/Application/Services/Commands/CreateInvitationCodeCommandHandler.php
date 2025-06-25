<?php

namespace RedJasmine\Invitation\Application\Services\Commands;

use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;
use RedJasmine\Invitation\Domain\Data\InvitationCodeData;
use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Models\Enums\InvitationCodeTypeEnum;
use RedJasmine\Invitation\Exceptions\InvitationException;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

/**
 * 创建邀请码命令处理器
 */
class CreateInvitationCodeCommandHandler extends CommandHandler
{
    public function __construct(
        protected InvitationCodeApplicationService $service
    ) {
        parent::__construct($service);
    }

    /**
     * @param InvitationCodeData $command
     * @return InvitationCode
     * @throws AbstractException
     * @throws Throwable
     */
    public function handle(InvitationCodeData $command): InvitationCode
    {
        $this->context->setCommand($command);
        
        $this->beginDatabaseTransaction();
        
        try {
            // 验证命令
            $this->service->hook('create.validate', $this->context, fn() => $this->validate($this->context));
            
            // 创建模型
            $model = $this->newModel($command);
            $this->context->setModel($model);
            
            // 填充数据
            $this->service->hook('create.fill', $this->context, fn() => $this->fill($this->context));
            
            // 保存模型
            $this->service->repository->store($this->context->getModel());
            
            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $this->context->getModel();
    }

    /**
     * 验证命令
     */
    protected function validate(HandleContext $context): void
    {
        /** @var InvitationCodeData $command */
        $command = $context->getCommand();

        // 如果是自定义邀请码，验证邀请码是否已存在
        if ($command->codeType === InvitationCodeTypeEnum::CUSTOM) {
            if (empty($command->code)) {
                throw new InvitationException('自定义邀请码不能为空');
            }

            if ($this->service->codeExists($command->code)) {
                throw new InvitationException('邀请码已存在');
            }
        }

        // 验证使用次数
        if ($command->maxUsage < 0) {
            throw new InvitationException('最大使用次数不能小于0');
        }

        // 验证过期时间
        if ($command->expiredAt && $command->expiredAt->isPast()) {
            throw new InvitationException('过期时间不能早于当前时间');
        }
    }

    /**
     * 填充数据
     */
    protected function fill(HandleContext $context): InvitationCode
    {
        /** @var InvitationCodeData $command */
        $command = $context->getCommand();
        /** @var InvitationCode $model */
        $model = $context->getModel();

        // 使用转换器填充数据
        $model = $this->service->transformer->transform($command, $model);

        // 生成邀请码
        if ($command->codeType === InvitationCodeTypeEnum::SYSTEM || empty($model->code)) {
            $model->code = InvitationCode::generateSystemCode();
        }

        $context->setModel($model);
        
        return $model;
    }

    /**
     * 创建新模型实例
     */
    protected function newModel($command): InvitationCode
    {
        return new InvitationCode();
    }
} 