<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

/**
 * 标记消息为已读命令
 */
class MessageMarkAsReadCommand extends Data
{
    public function __construct(
        public int|array $messageIds,
        public UserInterface $reader,
        public bool $markAll = false,
        public ?string $biz = null,
        public ?int $categoryId = null,
    ) {
    }

    /**
     * 验证命令数据
     */
    public function validate(): void
    {
        if (!$this->markAll) {
            if (is_int($this->messageIds)) {
                if ($this->messageIds <= 0) {
                    throw new \InvalidArgumentException('消息ID无效');
                }
            } elseif (is_array($this->messageIds)) {
                if (empty($this->messageIds)) {
                    throw new \InvalidArgumentException('消息ID列表不能为空');
                }
                
                foreach ($this->messageIds as $id) {
                    if (!is_int($id) || $id <= 0) {
                        throw new \InvalidArgumentException('消息ID无效');
                    }
                }
            } else {
                throw new \InvalidArgumentException('消息ID格式无效');
            }
        }
    }

    /**
     * 获取读者ID
     */
    public function getReaderId(): string
    {
        return (string) $this->reader->getKey();
    }

    /**
     * 获取消息ID数组
     */
    public function getMessageIds(): array
    {
        if (is_int($this->messageIds)) {
            return [$this->messageIds];
        }
        
        return $this->messageIds;
    }

    /**
     * 是否标记全部消息
     */
    public function isMarkAll(): bool
    {
        return $this->markAll;
    }

    /**
     * 获取业务线
     */
    public function getBiz(): ?string
    {
        return $this->biz;
    }

    /**
     * 获取分类ID
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }
}
