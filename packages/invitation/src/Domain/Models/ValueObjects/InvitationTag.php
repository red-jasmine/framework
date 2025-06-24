<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

/**
 * 邀请标签值对象
 */
final class InvitationTag extends ValueObject
{
    public function __construct(
        public readonly string $name,   // 标签名称
        public readonly string $value   // 标签值
    ) {
        $this->validate();
    }

    /**
     * 验证数据有效性
     */
    protected function validate(): void
    {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('标签名称不能为空');
        }

        if (!preg_match('/^[a-z_][a-z0-9_]*$/', $this->name)) {
            throw new \InvalidArgumentException('标签名称只能包含小写字母、数字和下划线，且必须以字母或下划线开头');
        }

        if (strlen($this->name) > 50) {
            throw new \InvalidArgumentException('标签名称长度不能超过50个字符');
        }

        if (strlen($this->value) > 100) {
            throw new \InvalidArgumentException('标签值长度不能超过100个字符');
        }
    }

    /**
     * 转为字符串显示
     */
    public function toString(): string
    {
        return "{$this->name}: {$this->value}";
    }

    /**
     * 转为数组
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }

    /**
     * 从数组创建实例
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            value: $data['value'] ?? ''
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

        return $this->name === $other->name && $this->value === $other->value;
    }

    /**
     * 创建渠道标签
     */
    public static function channel(string $channel): self
    {
        return new self('channel', $channel);
    }

    /**
     * 创建来源标签
     */
    public static function source(string $source): self
    {
        return new self('source', $source);
    }

    /**
     * 创建分类标签
     */
    public static function category(string $category): self
    {
        return new self('category', $category);
    }

    /**
     * 创建活动标签
     */
    public static function activity(string $activity): self
    {
        return new self('activity', $activity);
    }
} 