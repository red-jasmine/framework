<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Queries;

use RedJasmine\Message\Domain\Models\Enums\StatusEnum;
use RedJasmine\Support\Application\Queries\PaginationQuery;
use RedJasmine\Support\Contracts\UserInterface;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 消息分类列表查询
 */
class MessageCategoryListQuery extends PaginationQuery
{
    public function __construct(
        public ?UserInterface $owner = null,
        public ?int $parentId = null,
        public ?string $biz = null,

        #[WithCast(EnumCast::class, StatusEnum::class)]
        public ?StatusEnum $status = null,

        public ?string $name = null,
        public ?string $description = null,
        public ?bool $isSystem = null,
        public ?bool $hasChildren = null,
        public ?bool $hasMessages = null,

        // 关联查询
        public ?array $include = null,

        // 分页参数
        int $page = 1,
        int $perPage = 15,
        ?string $sort = 'sort',
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

        if ($this->parentId !== null) $filters['parent_id'] = $this->parentId;
        if ($this->biz) $filters['biz'] = $this->biz;
        if ($this->status) $filters['status'] = $this->status->value;
        if ($this->name) $filters['name'] = $this->name;
        if ($this->description) $filters['description'] = $this->description;
        if ($this->isSystem !== null) $filters['is_system'] = $this->isSystem;
        if ($this->hasChildren !== null) $filters['has_children'] = $this->hasChildren;
        if ($this->hasMessages !== null) $filters['has_messages'] = $this->hasMessages;

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
     * 是否只查询根分类
     */
    public function isRootOnly(): bool
    {
        return $this->parentId === null;
    }

    /**
     * 是否只查询子分类
     */
    public function isChildrenOnly(): bool
    {
        return $this->parentId !== null;
    }

    /**
     * 是否只查询启用的分类
     */
    public function isEnabledOnly(): bool
    {
        return $this->status === StatusEnum::ENABLE;
    }

    /**
     * 是否只查询系统分类
     */
    public function isSystemOnly(): bool
    {
        return $this->isSystem === true;
    }

    /**
     * 是否只查询用户分类
     */
    public function isUserOnly(): bool
    {
        return $this->isSystem === false;
    }
}
