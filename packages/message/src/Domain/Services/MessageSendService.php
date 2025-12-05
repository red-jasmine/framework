<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Domain\Services;

use Exception;
use InvalidArgumentException;
use RedJasmine\Message\Domain\Data\MessageData;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Repositories\MessageRepositoryInterface;
use RedJasmine\Message\Domain\Repositories\MessageTemplateRepositoryInterface;
use RedJasmine\Support\Domain\Contracts\UserInterface;

/**
 * 消息发送领域服务
 */
class MessageSendService
{
    public function __construct(
        protected MessageRepositoryInterface $messageRepository,
        protected MessageTemplateRepositoryInterface $templateRepository,
        protected MessageTemplateService $templateService,
        protected MessageRuleService $ruleService,
    ) {
    }

    /**
     * 发送单条消息
     */
    public function send(MessageData $messageData) : Message
    {
        // 验证发送权限
        $this->validateSendPermission($messageData);

        // 处理模板消息
        if ($messageData->templateId) {
            $messageData = $this->processTemplateMessage($messageData);
        }

        // 验证消息内容
        $this->validateMessageContent($messageData);

        // 验证推送规则
        $this->ruleService->validatePushRules($messageData);

        // 创建消息实体
        $message = new Message();
        $message = $this->fillMessageFromData($message, $messageData);

        // 保存消息
        $this->messageRepository->store($message);

        return $message;
    }

    /**
     * 批量发送消息
     */
    public function batchSend(array $messageDatas) : array
    {
        $messages = [];

        foreach ($messageDatas as $messageData) {
            try {
                $message    = $this->send($messageData);
                $messages[] = $message;
            } catch (Exception $e) {
                // 记录发送失败的消息
                // Log::error('批量发送消息失败', [
                //     'message_data' => $messageData,
                //     'error' => $e->getMessage()
                // ]);

                // 可以选择继续发送其他消息或者停止批量发送
                throw $e;
            }
        }

        return $messages;
    }

    /**
     * 验证发送权限
     */
    public function validateSendPermission(MessageData $messageData) : void
    {
        // 验证发送人权限
        if ($messageData->senderId) {
            $this->validateSenderPermission($messageData->senderId, $messageData->biz);
        }

        // 验证接收人有效性
        $this->validateReceiverPermission($messageData->receiverId, $messageData->biz);

        // 验证分类权限
        if ($messageData->categoryId) {
            $this->validateCategoryPermission($messageData->categoryId, $messageData->owner);
        }

        // 验证模板权限
        if ($messageData->templateId) {
            $this->validateTemplatePermission($messageData->templateId);
        }
    }

    /**
     * 处理模板消息
     */
    protected function processTemplateMessage(MessageData $messageData) : MessageData
    {
        $template = $this->templateRepository->find($messageData->templateId);

        if (!$template) {
            throw new InvalidArgumentException('消息模板不存在');
        }

        if (!$template->isEnabled()) {
            throw new InvalidArgumentException('消息模板已被禁用');
        }

        // 获取模板变量
        $variables = $messageData->data['template_variables'] ?? [];

        // 渲染模板内容
        $renderedContent = $this->templateService->process($template, $variables);

        // 更新消息数据
        $messageData->title   = $renderedContent['title'];
        $messageData->content = $renderedContent['content'];

        return $messageData;
    }

    /**
     * 验证消息内容
     */
    protected function validateMessageContent(MessageData $messageData) : void
    {
        if (empty($messageData->title)) {
            throw new InvalidArgumentException('消息标题不能为空');
        }

        if (empty($messageData->content)) {
            throw new InvalidArgumentException('消息内容不能为空');
        }

        if (mb_strlen($messageData->title) > 255) {
            throw new InvalidArgumentException('消息标题长度不能超过255个字符');
        }

        // 验证消息内容长度
        if (mb_strlen($messageData->content) > 65535) {
            throw new InvalidArgumentException('消息内容过长');
        }

        // 验证敏感词（可以扩展）
        $this->validateSensitiveWords($messageData->title, $messageData->content);
    }

    /**
     * 从数据填充消息实体
     */
    protected function fillMessageFromData(Message $message, MessageData $messageData) : Message
    {
        $message->biz                = $messageData->biz;
        $message->category_id        = $messageData->categoryId;
        $message->receiver_id        = $messageData->receiverId;
        $message->sender_id          = $messageData->senderId;
        $message->template_id        = $messageData->templateId;
        $message->title              = $messageData->title;
        $message->content            = $messageData->content;
        $message->data               = $messageData->data;
        $message->source             = $messageData->source;
        $message->type               = $messageData->type;
        $message->priority           = $messageData->priority;
        $message->status             = $messageData->status;
        $message->channels           = array_map(
            fn($channel) => $channel->value,
            $messageData->getPushChannels()
        );
        $message->is_urgent          = $messageData->isUrgent;
        $message->is_burn_after_read = $messageData->isBurnAfterRead;
        $message->expires_at         = $messageData->expiresAt;
        $message->owner_id           = (string) $messageData->owner->getKey();
        $message->operator_id        = $messageData->operatorId;

        return $message;
    }

    /**
     * 验证发送人权限
     */
    protected function validateSenderPermission(?string $senderId, $biz) : void
    {
        if (!$senderId) {
            return;
        }

        // 这里可以实现具体的发送人权限验证逻辑
        // 例如：检查发送人是否有权限向指定业务线发送消息
    }

    /**
     * 验证接收人权限
     */
    protected function validateReceiverPermission(?string $receiverId, $biz) : void
    {
        if (!$receiverId) {
            throw new InvalidArgumentException('接收人不能为空');
        }

        // 这里可以实现具体的接收人验证逻辑
        // 例如：检查接收人是否存在、是否活跃等
    }

    /**
     * 验证分类权限
     */
    protected function validateCategoryPermission(?int $categoryId, UserInterface $owner) : void
    {
        if (!$categoryId) {
            return;
        }

        // 这里可以实现具体的分类权限验证逻辑
        // 例如：检查分类是否属于当前用户、是否启用等
    }

    /**
     * 验证模板权限
     */
    protected function validateTemplatePermission(?int $templateId) : void
    {
        if (!$templateId) {
            return;
        }

        $template = $this->templateRepository->find($templateId);

        if (!$template) {
            throw new InvalidArgumentException('消息模板不存在');
        }

        if (!$template->isEnabled()) {
            throw new InvalidArgumentException('消息模板已被禁用');
        }
    }

    /**
     * 验证敏感词
     */
    protected function validateSensitiveWords(string $title, string $content) : void
    {
        // 这里可以实现敏感词检测逻辑
        // 例如：调用第三方敏感词检测服务

        $sensitiveWords = $this->getSensitiveWords();

        foreach ($sensitiveWords as $word) {
            if (str_contains($title, $word) || str_contains($content, $word)) {
                throw new InvalidArgumentException("消息内容包含敏感词: {$word}");
            }
        }
    }

    /**
     * 获取敏感词列表
     */
    protected function getSensitiveWords() : array
    {
        // 这里可以从配置文件或数据库中获取敏感词列表
        return config('message.sensitive_words', []);
    }
}
