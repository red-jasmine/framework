<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Queries;

use RedJasmine\Support\Application\Queries\FindQuery;
use RedJasmine\Support\Contracts\UserInterface;

/**
 * 查找消息查询
 */
class MessageFindQuery extends FindQuery
{
    public function __construct(
        public int $id,
        public ?UserInterface $owner = null,
        public ?array $include = null,
        public bool $checkPermission = true,
    ) {
        parent::__construct($id);
    }

    /**
     * 获取所属者ID
     */
    public function getOwnerId(): ?string
    {
        return $this->owner ? (string) $this->owner->getKey() : null;
    }

    /**
     * 获取包含的关联
     */
    public function getIncludes(): array
    {
        return $this->include ?? [];
    }

    /**
     * 是否检查权限
     */
    public function shouldCheckPermission(): bool
    {
        return $this->checkPermission;
    }
}
