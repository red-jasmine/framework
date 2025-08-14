<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Commands;

use RedJasmine\Message\Application\Services\MessageApplicationService;
use RedJasmine\Message\Domain\Data\MessageData;
use RedJasmine\Message\Domain\Events\MessageSent;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Services\MessageSendService;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

/**
 * 发送消息命令处理器
 */
class MessageSendCommandHandler extends CommandHandler
{
    public function __construct(
        protected MessageApplicationService $service,
        protected MessageSendService $sendService,
    ) {
    }

    /**
     * 处理发送消息命令
     */
    public function handle(MessageSendCommand $command): array
    {
        $this->beginDatabaseTransaction();

        try {
            // 验证命令数据
            $command->validate();

            $messages = [];
            $receivers = $command->getReceivers();

            // 为每个接收人创建消息
            foreach ($receivers as $receiver) {
                $messageData = $this->createMessageData($command, $receiver);
                $message = $this->sendService->send($messageData);
                $messages[] = $message;

                // 发布消息发送事件
                event(new MessageSent($message, $command->sender));
            }

            $this->commitDatabaseTransaction();

            return $messages;

        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }

    /**
     * 创建消息数据
     */
    protected function createMessageData(MessageSendCommand $command, $receiver): MessageData
    {
        return new MessageData(
            owner: $command->sender,
            sender: $command->sender,
            receiver: $receiver,
            biz: $command->biz,
            categoryId: $command->categoryId,
            templateId: $command->templateId,
            title: $command->title,
            content: $command->content,
            data: $this->buildMessageData($command),
            type: $command->type,
            priority: $command->priority,
            channels: $command->getPushChannels(),
            isUrgent: $command->isUrgent,
            isBurnAfterRead: $command->isBurnAfterRead,
            expiresAt: $command->expiresAt,
        );
    }

    /**
     * 构建消息数据
     */
    protected function buildMessageData(MessageSendCommand $command): array
    {
        $data = $command->data ?? [];

        // 添加模板变量
        if ($command->isUsingTemplate()) {
            $data['template_variables'] = $command->getTemplateVariables();
        }

        // 添加推送参数
        $data['push_parameters'] = $command->getPushParameters();

        // 添加重试配置
        if ($command->getRetryConfig()) {
            $data['retry_config'] = $command->getRetryConfig();
        }

        // 添加延迟配置
        if ($command->getDelaySeconds()) {
            $data['delay_seconds'] = $command->getDelaySeconds();
        }

        // 添加立即发送标志
        $data['send_immediately'] = $command->isSendImmediately();

        return $data;
    }

    /**
     * 验证发送权限
     */
    protected function validateSendPermission(MessageSendCommand $command): void
    {
        // 验证发送人权限
        // 验证接收人有效性
        // 验证业务线权限
        // 验证分类权限
        // 验证模板权限
    }

    /**
     * 验证发送频率
     */
    protected function validateSendFrequency(MessageSendCommand $command): void
    {
        // 检查发送频率限制
        // 可以基于发送人、业务线、时间窗口等进行限制
    }

    /**
     * 验证接收人数量限制
     */
    protected function validateReceiverLimit(MessageSendCommand $command): void
    {
        $receivers = $command->getReceivers();
        $maxReceivers = config('message.max_receivers_per_send', 100);

        if (count($receivers) > $maxReceivers) {
            throw new \InvalidArgumentException("接收人数量不能超过 {$maxReceivers} 个");
        }
    }
}
