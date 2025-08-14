<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Queries;

use RedJasmine\Support\Application\Queries\Query;
use RedJasmine\Support\Contracts\UserInterface;

/**
 * 消息统计查询
 */
class MessageStatisticsQuery extends Query
{
    public function __construct(
        public ?UserInterface $owner = null,
        public ?string $receiverId = null,
        public ?string $biz = null,
        public ?int $categoryId = null,
        public ?string $startDate = null,
        public ?string $endDate = null,
        public array $dimensions = ['total', 'unread', 'read', 'archived', 'urgent', 'expired'],
        public bool $groupByBiz = false,
        public bool $groupByCategory = false,
        public bool $groupByDate = false,
        public string $dateFormat = 'Y-m-d',
    ) {
    }

    /**
     * 获取所属者ID
     */
    public function getOwnerId(): ?string
    {
        return $this->owner ? (string) $this->owner->getKey() : null;
    }

    /**
     * 获取统计维度
     */
    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    /**
     * 是否按业务线分组
     */
    public function isGroupByBiz(): bool
    {
        return $this->groupByBiz;
    }

    /**
     * 是否按分类分组
     */
    public function isGroupByCategory(): bool
    {
        return $this->groupByCategory;
    }

    /**
     * 是否按日期分组
     */
    public function isGroupByDate(): bool
    {
        return $this->groupByDate;
    }

    /**
     * 获取日期格式
     */
    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    /**
     * 获取时间范围
     */
    public function getDateRange(): array
    {
        return [
            'start' => $this->startDate,
            'end' => $this->endDate,
        ];
    }

    /**
     * 是否有时间范围限制
     */
    public function hasDateRange(): bool
    {
        return !empty($this->startDate) && !empty($this->endDate);
    }
}
