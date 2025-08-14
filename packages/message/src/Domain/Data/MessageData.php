<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Domain\Data;

use DateTimeInterface;
use RedJasmine\Message\Domain\Models\Enums\MessagePriorityEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageSourceEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageStatusEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageTypeEnum;
use RedJasmine\Message\Domain\Models\Enums\PushChannelEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;


/**
 * 消息数据传输对象
 */
class MessageData extends Data
{
    public function __construct(
        public UserInterface $owner,
        public ?UserInterface $sender,
        public ?UserInterface $source,

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

    ) {
    }

    /**
     * 获取推送渠道枚举数组
     * @return PushChannelEnum[]
     */
    public function getPushChannels() : array
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
     * 设置推送渠道
     *
     * @param  PushChannelEnum[]  $channels
     */
    public function setPushChannels(array $channels) : self
    {
        $this->channels = array_map(
            fn($channel) => $channel instanceof PushChannelEnum ? $channel->value : $channel,
            $channels
        );

        return $this;
    }

    /**
     * 是否包含指定推送渠道
     */
    public function hasChannel(PushChannelEnum $channel) : bool
    {
        return in_array($channel, $this->getPushChannels(), true);
    }

    /**
     * 添加推送渠道
     */
    public function addChannel(PushChannelEnum $channel) : self
    {
        $channels = $this->getPushChannels();

        if (!in_array($channel, $channels, true)) {
            $channels[] = $channel;
            $this->setPushChannels($channels);
        }

        return $this;
    }

    /**
     * 移除推送渠道
     */
    public function removeChannel(PushChannelEnum $channel) : self
    {
        $channels         = $this->getPushChannels();
        $filteredChannels = array_filter(
            $channels,
            fn($c) => $c !== $channel
        );

        $this->setPushChannels(array_values($filteredChannels));

        return $this;
    }

    /**
     * 是否为高优先级消息
     */
    public function isHighPriority() : bool
    {
        return in_array($this->priority, [MessagePriorityEnum::HIGH, MessagePriorityEnum::URGENT], true);
    }

    /**
     * 是否为紧急消息
     */
    public function isUrgentMessage() : bool
    {
        return $this->priority === MessagePriorityEnum::URGENT || $this->isUrgent;
    }

    /**
     * 获取消息数据
     */
    public function getMessageData(string $key, mixed $default = null) : mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * 设置消息数据
     */
    public function setMessageData(string $key, mixed $value) : self
    {
        if ($this->data === null) {
            $this->data = [];
        }

        $this->data[$key] = $value;

        return $this;
    }

    /**
     * 合并消息数据
     */
    public function mergeMessageData(array $data) : self
    {
        $this->data = array_merge($this->data ?? [], $data);

        return $this;
    }
}
