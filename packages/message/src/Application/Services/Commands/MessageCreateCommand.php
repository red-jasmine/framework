<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Commands;

use DateTimeInterface;
use RedJasmine\Message\Domain\Data\MessageData;
use RedJasmine\Message\Domain\Models\Enums\MessagePriorityEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageStatusEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageTypeEnum;
use RedJasmine\Message\Domain\Models\Enums\PushChannelEnum;
use RedJasmine\Support\Contracts\UserInterface;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 创建消息命令
 */
class MessageCreateCommand extends MessageData
{
    public function __construct(
        public UserInterface $owner,
        public ?UserInterface $sender = null,
        public ?UserInterface $receiver = null,

        public string $biz,

        public ?int $categoryId = null,
        public ?int $templateId = null,
        public ?string $title = null,
        public ?string $content = null,
        public ?array $data = null,

        #[WithCast(EnumCast::class, MessageTypeEnum::class)]
        public MessageTypeEnum $type = MessageTypeEnum::NOTIFICATION,

        #[WithCast(EnumCast::class, MessagePriorityEnum::class)]
        public MessagePriorityEnum $priority = MessagePriorityEnum::NORMAL,

        #[WithCast(EnumCast::class, MessageStatusEnum::class)]
        public MessageStatusEnum $status = MessageStatusEnum::UNREAD,

        public ?DateTimeInterface $readAt = null,

        /** @var PushChannelEnum[] */
        public ?array $channels = null,

        public bool $isUrgent = false,
        public bool $isBurnAfterRead = false,
        public ?DateTimeInterface $expiresAt = null,

        // 扩展字段
        public bool $sendImmediately = true,
        public ?array $templateVariables = null,
        public ?array $pushParameters = null,
    ) {
        parent::__construct(
            $owner,
            $sender,
            $receiver,
            $biz,
            $categoryId,
            $templateId,
            $title,
            $content,
            $data,
            $type,
            $priority,
            $status,
            $readAt,
            $channels,
            $isUrgent,
            $isBurnAfterRead,
            $expiresAt
        );
    }

    /**
     * 验证命令数据
     */
    public function validate(): void
    {
        if (empty($this->title)) {
            throw new \InvalidArgumentException('消息标题不能为空');
        }

        if (empty($this->content) && empty($this->templateId)) {
            throw new \InvalidArgumentException('消息内容或模板ID不能同时为空');
        }

        if (!$this->receiver) {
            throw new \InvalidArgumentException('接收人不能为空');
        }

        if ($this->expiresAt && $this->expiresAt < now()) {
            throw new \InvalidArgumentException('过期时间不能早于当前时间');
        }
    }

    /**
     * 是否使用模板
     */
    public function isUsingTemplate(): bool
    {
        return !empty($this->templateId);
    }

    /**
     * 获取模板变量
     */
    public function getTemplateVariables(): array
    {
        return $this->templateVariables ?? [];
    }

    /**
     * 获取推送参数
     */
    public function getPushParameters(): array
    {
        return $this->pushParameters ?? [];
    }

    /**
     * 设置接收人ID
     */
    public function setReceiverId(): void
    {
        if ($this->receiver) {
            $this->receiverId = (string) $this->receiver->getKey();
        }
    }

    /**
     * 设置发送人ID
     */
    public function setSenderId(): void
    {
        if ($this->sender) {
            $this->senderId = (string) $this->sender->getKey();
        }
    }
}
