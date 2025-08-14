<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Commands;

use DateTimeInterface;
use RedJasmine\Message\Domain\Models\Enums\MessagePriorityEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageStatusEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageTypeEnum;
use RedJasmine\Message\Domain\Models\Enums\PushChannelEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 更新消息命令
 */
class MessageUpdateCommand extends Data
{
    public function __construct(
        public int $id,
        public UserInterface $operator,

        public ?int $categoryId = null,
        public ?array $data = null,

        #[WithCast(EnumCast::class, MessageTypeEnum::class)]
        public ?MessageTypeEnum $type = null,

        #[WithCast(EnumCast::class, MessagePriorityEnum::class)]
        public ?MessagePriorityEnum $priority = null,

        #[WithCast(EnumCast::class, MessageStatusEnum::class)]
        public ?MessageStatusEnum $status = null,

        /** @var PushChannelEnum[] */
        public ?array $channels = null,

        public ?bool $isUrgent = null,
        public ?bool $isBurnAfterRead = null,
        public ?DateTimeInterface $expiresAt = null,

        // 扩展字段
        public ?array $pushParameters = null,
    ) {
    }

    /**
     * 验证命令数据
     */
    public function validate(): void
    {
        if ($this->id <= 0) {
            throw new \InvalidArgumentException('消息ID无效');
        }

        if ($this->expiresAt && $this->expiresAt < now()) {
            throw new \InvalidArgumentException('过期时间不能早于当前时间');
        }
    }

    /**
     * 获取操作人ID
     */
    public function getOperatorId(): string
    {
        return (string) $this->operator->getKey();
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
     * 是否有更新数据
     */
    public function hasUpdates(): bool
    {
        return $this->categoryId !== null
            || $this->data !== null
            || $this->type !== null
            || $this->priority !== null
            || $this->status !== null
            || $this->channels !== null
            || $this->isUrgent !== null
            || $this->isBurnAfterRead !== null
            || $this->expiresAt !== null
            || $this->pushParameters !== null;
    }

    /**
     * 获取更新的字段
     */
    public function getUpdatedFields(): array
    {
        $fields = [];

        if ($this->categoryId !== null) $fields['category_id'] = $this->categoryId;
        if ($this->data !== null) $fields['data'] = $this->data;
        if ($this->type !== null) $fields['type'] = $this->type;
        if ($this->priority !== null) $fields['priority'] = $this->priority;
        if ($this->status !== null) $fields['status'] = $this->status;
        if ($this->channels !== null) {
            $fields['channels'] = array_map(
                fn($channel) => $channel instanceof PushChannelEnum ? $channel->value : $channel,
                $this->channels
            );
        }
        if ($this->isUrgent !== null) $fields['is_urgent'] = $this->isUrgent;
        if ($this->isBurnAfterRead !== null) $fields['is_burn_after_read'] = $this->isBurnAfterRead;
        if ($this->expiresAt !== null) $fields['expires_at'] = $this->expiresAt;

        return $fields;
    }
}
