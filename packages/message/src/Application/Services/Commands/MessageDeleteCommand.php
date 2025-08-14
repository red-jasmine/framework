<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

/**
 * 删除消息命令
 */
class MessageDeleteCommand extends Data
{
    public function __construct(
        public int $id,
        public UserInterface $operator,
        public bool $forceDelete = false,
        public ?string $reason = null,
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
    }

    /**
     * 获取操作人ID
     */
    public function getOperatorId(): string
    {
        return (string) $this->operator->getKey();
    }

    /**
     * 是否强制删除
     */
    public function isForceDelete(): bool
    {
        return $this->forceDelete;
    }

    /**
     * 获取删除原因
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }
}
