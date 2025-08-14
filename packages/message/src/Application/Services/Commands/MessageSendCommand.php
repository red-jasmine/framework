<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Commands;

use DateTimeInterface;
use RedJasmine\Message\Domain\Models\Enums\MessagePriorityEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageTypeEnum;
use RedJasmine\Message\Domain\Models\Enums\PushChannelEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 发送消息命令
 */
class MessageSendCommand extends Data
{
    public function __construct(
        public UserInterface $sender,
        public UserInterface|array $receivers,
        public string $biz,

        public string $title,
        public string $content,

        public ?int $categoryId = null,
        public ?int $templateId = null,
        public ?array $templateVariables = null,
        public ?array $data = null,

        #[WithCast(EnumCast::class, MessageTypeEnum::class)]
        public MessageTypeEnum $type = MessageTypeEnum::NOTIFICATION,

        #[WithCast(EnumCast::class, MessagePriorityEnum::class)]
        public MessagePriorityEnum $priority = MessagePriorityEnum::NORMAL,

        /** @var PushChannelEnum[] */
        public ?array $channels = null,

        public bool $isUrgent = false,
        public bool $isBurnAfterRead = false,
        public ?DateTimeInterface $expiresAt = null,
        public bool $sendImmediately = true,

        // 推送配置
        public ?array $pushParameters = null,
        public ?int $delaySeconds = null,
        public ?array $retryConfig = null,
    ) {
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

        if (empty($this->biz)) {
            throw new \InvalidArgumentException('业务线不能为空');
        }

        if ($this->expiresAt && $this->expiresAt < now()) {
            throw new \InvalidArgumentException('过期时间不能早于当前时间');
        }

        $receivers = $this->getReceivers();
        if (empty($receivers)) {
            throw new \InvalidArgumentException('接收人不能为空');
        }
    }

    /**
     * 获取发送人ID
     */
    public function getSenderId(): string
    {
        return (string) $this->sender->getKey();
    }

    /**
     * 获取接收人列表
     */
    public function getReceivers(): array
    {
        if ($this->receivers instanceof UserInterface) {
            return [$this->receivers];
        }

        return $this->receivers;
    }

    /**
     * 获取接收人ID列表
     */
    public function getReceiverIds(): array
    {
        $receivers = $this->getReceivers();
        return array_map(fn($receiver) => (string) $receiver->getKey(), $receivers);
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
     * 获取推送渠道枚举数组
     */
    public function getPushChannels(): array
    {
        if ($this->channels === null) {
            return [];
        }

        $channels = [];
        foreach ($this->channels as $channel) {
            if (is_string($channel)) {
                $channels[] = PushChannelEnum::from($channel);
            } elseif ($channel instanceof PushChannelEnum) {
                $channels[] = $channel;
            }
        }

        return $channels;
    }

    /**
     * 获取推送参数
     */
    public function getPushParameters(): array
    {
        return $this->pushParameters ?? [];
    }

    /**
     * 获取重试配置
     */
    public function getRetryConfig(): array
    {
        return $this->retryConfig ?? [];
    }

    /**
     * 是否立即发送
     */
    public function isSendImmediately(): bool
    {
        return $this->sendImmediately;
    }

    /**
     * 获取延迟秒数
     */
    public function getDelaySeconds(): ?int
    {
        return $this->delaySeconds;
    }
}
