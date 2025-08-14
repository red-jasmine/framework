<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Queries;

use RedJasmine\Message\Domain\Models\Enums\MessagePriorityEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageStatusEnum;
use RedJasmine\Message\Domain\Models\Enums\MessageTypeEnum;
use RedJasmine\Message\Domain\Models\Enums\PushStatusEnum;
use RedJasmine\Support\Application\Queries\PaginationQuery;
use RedJasmine\Support\Contracts\UserInterface;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 消息列表查询
 */
class MessageListQuery extends PaginationQuery
{
    public function __construct(
        public ?UserInterface $owner = null,
        public ?string $receiverId = null,
        public ?string $senderId = null,
        public ?string $biz = null,
        public ?int $categoryId = null,
        public ?int $templateId = null,

        #[WithCast(EnumCast::class, MessageTypeEnum::class)]
        public ?MessageTypeEnum $type = null,

        #[WithCast(EnumCast::class, MessagePriorityEnum::class)]
        public ?MessagePriorityEnum $priority = null,

        #[WithCast(EnumCast::class, MessageStatusEnum::class)]
        public ?MessageStatusEnum $status = null,

        #[WithCast(EnumCast::class, PushStatusEnum::class)]
        public ?PushStatusEnum $pushStatus = null,

        public ?string $title = null,
        public ?string $content = null,
        public ?bool $isUrgent = null,
        public ?bool $isBurnAfterRead = null,
        public ?array $channels = null,

        // 时间范围查询
        public ?string $createdStart = null,
        public ?string $createdEnd = null,
        public ?string $readStart = null,
        public ?string $readEnd = null,
        public ?string $expiresStart = null,
        public ?string $expiresEnd = null,

        // 特殊查询
        public ?bool $hasAttachment = null,
        public ?bool $isExpired = null,
        public ?bool $isHighPriority = null,

        // 关联查询
        public ?array $include = null,

        // 分页参数
        int $page = 1,
        int $perPage = 15,
        ?string $sort = '-created_at',
    ) {
        parent::__construct($page, $perPage, $sort);
    }

    /**
     * 获取所属者ID
     */
    public function getOwnerId(): ?string
    {
        return $this->owner ? (string) $this->owner->getKey() : null;
    }

    /**
     * 获取过滤条件
     */
    public function getFilters(): array
    {
        $filters = [];

        if ($this->receiverId) $filters['receiver_id'] = $this->receiverId;
        if ($this->senderId) $filters['sender_id'] = $this->senderId;
        if ($this->biz) $filters['biz'] = $this->biz;
        if ($this->categoryId) $filters['category_id'] = $this->categoryId;
        if ($this->templateId) $filters['template_id'] = $this->templateId;
        if ($this->type) $filters['type'] = $this->type->value;
        if ($this->priority) $filters['priority'] = $this->priority->value;
        if ($this->status) $filters['status'] = $this->status->value;
        if ($this->pushStatus) $filters['push_status'] = $this->pushStatus->value;
        if ($this->title) $filters['title'] = $this->title;
        if ($this->content) $filters['content'] = $this->content;
        if ($this->isUrgent !== null) $filters['is_urgent'] = $this->isUrgent;
        if ($this->isBurnAfterRead !== null) $filters['is_burn_after_read'] = $this->isBurnAfterRead;

        // 时间范围过滤
        if ($this->createdStart && $this->createdEnd) {
            $filters['created_between'] = [$this->createdStart, $this->createdEnd];
        }
        if ($this->readStart && $this->readEnd) {
            $filters['read_between'] = [$this->readStart, $this->readEnd];
        }
        if ($this->expiresStart && $this->expiresEnd) {
            $filters['expires_between'] = [$this->expiresStart, $this->expiresEnd];
        }

        // 特殊过滤
        if ($this->hasAttachment !== null) $filters['has_attachment'] = $this->hasAttachment;
        if ($this->isExpired !== null) {
            if ($this->isExpired) {
                $filters['expires_before'] = now()->toDateTimeString();
            } else {
                $filters['not_expired'] = true;
            }
        }
        if ($this->isHighPriority !== null && $this->isHighPriority) {
            $filters['high_priority'] = true;
        }

        // 渠道过滤
        if ($this->channels) {
            foreach ($this->channels as $channel) {
                $filters['channel'] = $channel;
                break; // 目前只支持单个渠道过滤
            }
        }

        return $filters;
    }

    /**
     * 获取包含的关联
     */
    public function getIncludes(): array
    {
        return $this->include ?? [];
    }

    /**
     * 是否只查询未读消息
     */
    public function isUnreadOnly(): bool
    {
        return $this->status === MessageStatusEnum::UNREAD;
    }

    /**
     * 是否只查询高优先级消息
     */
    public function isHighPriorityOnly(): bool
    {
        return $this->isHighPriority === true || 
               in_array($this->priority, [MessagePriorityEnum::HIGH, MessagePriorityEnum::URGENT]);
    }

    /**
     * 是否只查询紧急消息
     */
    public function isUrgentOnly(): bool
    {
        return $this->isUrgent === true || $this->priority === MessagePriorityEnum::URGENT;
    }
}
