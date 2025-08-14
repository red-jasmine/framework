<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Message\Domain\Models\MessageCategory;

/**
 * 消息分类创建事件
 */
class MessageCategoryCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly MessageCategory $category
    ) {
    }

    /**
     * 获取事件标识
     */
    public function getEventId(): string
    {
        return 'message.category.created';
    }

    /**
     * 获取分类ID
     */
    public function getCategoryId(): int
    {
        return $this->category->id;
    }

    /**
     * 获取分类名称
     */
    public function getCategoryName(): string
    {
        return $this->category->name;
    }

    /**
     * 获取业务线
     */
    public function getBiz(): string
    {
        return $this->category->biz->value;
    }

    /**
     * 获取所属者ID
     */
    public function getOwnerId(): string
    {
        return $this->category->owner_id;
    }

    /**
     * 是否启用
     */
    public function isEnabled(): bool
    {
        return $this->category->isEnabled();
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'event_id' => $this->getEventId(),
            'category_id' => $this->getCategoryId(),
            'category_name' => $this->getCategoryName(),
            'biz' => $this->getBiz(),
            'owner_id' => $this->getOwnerId(),
            'is_enabled' => $this->isEnabled(),
            'created_at' => $this->category->created_at?->toISOString(),
        ];
    }
}
