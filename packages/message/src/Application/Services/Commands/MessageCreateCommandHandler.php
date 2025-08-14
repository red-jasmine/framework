<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Commands;

use RedJasmine\Message\Application\Services\MessageApplicationService;
use RedJasmine\Message\Domain\Events\MessageCreated;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Services\MessageSendService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

/**
 * 创建消息命令处理器
 */
class MessageCreateCommandHandler extends CommandHandler
{
    public function __construct(
        protected MessageApplicationService $service,
        protected MessageSendService $sendService,
    ) {
    }

    /**
     * 处理创建消息命令
     */
    public function handle(MessageCreateCommand $command): Message
    {
        $this->beginDatabaseTransaction();

        try {
            // 验证命令数据
            $command->validate();

            // 设置接收人和发送人ID
            $command->setReceiverId();
            $command->setSenderId();

            // 创建消息实体
            $message = $this->service->newModel();
            $message = $this->service->transformer->transform($command, $message);

            // 保存消息
            $this->service->repository->store($message);

            // 发布消息创建事件
            event(new MessageCreated($message, $command->owner));

            // 如果需要立即发送，则加入发送队列
            if ($command->sendImmediately) {
                $this->queueForSending($message);
            }

            $this->commitDatabaseTransaction();

            return $message;

        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }

    /**
     * 加入发送队列
     */
    protected function queueForSending(Message $message): void
    {
        // 这里可以将消息加入发送队列
        // 例如：dispatch(new SendMessageJob($message));
        
        // 或者直接调用发送服务
        // $this->sendService->queueMessage($message);
    }

    /**
     * 验证业务规则
     */
    protected function validateBusinessRules(MessageCreateCommand $command): void
    {
        // 验证分类权限
        if ($command->categoryId) {
            $this->validateCategoryPermission($command);
        }

        // 验证模板权限
        if ($command->templateId) {
            $this->validateTemplatePermission($command);
        }

        // 验证发送频率限制
        $this->validateSendFrequency($command);
    }

    /**
     * 验证分类权限
     */
    protected function validateCategoryPermission(MessageCreateCommand $command): void
    {
        // 检查分类是否存在且可用
        // 检查用户是否有权限使用该分类
    }

    /**
     * 验证模板权限
     */
    protected function validateTemplatePermission(MessageCreateCommand $command): void
    {
        // 检查模板是否存在且可用
        // 检查用户是否有权限使用该模板
    }

    /**
     * 验证发送频率限制
     */
    protected function validateSendFrequency(MessageCreateCommand $command): void
    {
        // 检查用户发送频率是否超限
        // 可以基于时间窗口、用户级别等进行限制
    }
}
