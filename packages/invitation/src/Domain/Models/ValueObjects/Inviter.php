<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

/**
 * 邀请人值对象
 */
final class Inviter extends ValueObject
{
    public function __construct(
        public readonly string $type,  // 邀请人类型
        public readonly string $id,    // 邀请人ID
        public readonly string $name   // 邀请人姓名
    ) {
        $this->validate();
    }

    /**
     * 验证数据有效性
     */
    protected function validate(): void
    {
        if (empty($this->type)) {
            throw new \InvalidArgumentException('邀请人类型不能为空');
        }

        if (empty($this->id)) {
            throw new \InvalidArgumentException('邀请人ID不能为空');
        }

        if (empty($this->name)) {
            throw new \InvalidArgumentException('邀请人姓名不能为空');
        }

        if (strlen($this->type) > 50) {
            throw new \InvalidArgumentException('邀请人类型长度不能超过50个字符');
        }

        if (strlen($this->id) > 100) {
            throw new \InvalidArgumentException('邀请人ID长度不能超过100个字符');
        }

        if (strlen($this->name) > 100) {
            throw new \InvalidArgumentException('邀请人姓名长度不能超过100个字符');
        }
    }

    /**
     * 转为字符串显示
     */
    public function toString(): string
    {
        return "{$this->name}({$this->type}:{$this->id})";
    }

    /**
     * 转为数组
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    /**
     * 从数组创建实例
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'] ?? '',
            id: $data['id'] ?? '',
            name: $data['name'] ?? ''
        );
    }

    /**
     * 检查是否相等
     */
    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->type === $other->type && $this->id === $other->id;
    }
} 