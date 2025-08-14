<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

/**
 * 模板变量值对象
 */
class TemplateVariable extends ValueObject
{
    /**
     * 变量名称
     */
    public readonly string $name;

    /**
     * 变量类型 (string, number, boolean, array, object)
     */
    public readonly string $type;

    /**
     * 默认值
     */
    public readonly mixed $defaultValue;

    /**
     * 是否必需
     */
    public readonly bool $required;

    /**
     * 验证规则
     */
    public readonly array $validationRules;

    /**
     * 描述信息
     */
    public readonly string $description;

    /**
     * 示例值
     */
    public readonly mixed $example;

    public function __construct(
        string $name,
        string $type = 'string',
        mixed $defaultValue = null,
        bool $required = false,
        array $validationRules = [],
        string $description = '',
        mixed $example = null
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->defaultValue = $defaultValue;
        $this->required = $required;
        $this->validationRules = $validationRules;
        $this->description = $description;
        $this->example = $example;

        $this->validate();
    }

    /**
     * 验证变量定义
     */
    protected function validate(): void
    {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('变量名称不能为空');
        }

        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $this->name)) {
            throw new \InvalidArgumentException('变量名称格式不正确');
        }

        $validTypes = ['string', 'number', 'boolean', 'array', 'object'];
        if (!in_array($this->type, $validTypes, true)) {
            throw new \InvalidArgumentException('不支持的变量类型');
        }

        if ($this->required && $this->defaultValue !== null) {
            // 验证默认值类型是否匹配
            $this->validateValueType($this->defaultValue);
        }
    }

    /**
     * 验证值的类型是否匹配
     */
    public function validateValueType(mixed $value): bool
    {
        return match ($this->type) {
            'string' => is_string($value),
            'number' => is_numeric($value),
            'boolean' => is_bool($value),
            'array' => is_array($value),
            'object' => is_object($value) || is_array($value),
            default => false,
        };
    }

    /**
     * 验证值是否符合规则
     */
    public function validateValue(mixed $value): bool
    {
        // 检查类型
        if (!$this->validateValueType($value)) {
            return false;
        }

        // 应用验证规则
        foreach ($this->validationRules as $rule => $constraint) {
            if (!$this->applyValidationRule($value, $rule, $constraint)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 应用验证规则
     */
    protected function applyValidationRule(mixed $value, string $rule, mixed $constraint): bool
    {
        return match ($rule) {
            'min_length' => is_string($value) && mb_strlen($value) >= $constraint,
            'max_length' => is_string($value) && mb_strlen($value) <= $constraint,
            'min_value' => is_numeric($value) && $value >= $constraint,
            'max_value' => is_numeric($value) && $value <= $constraint,
            'pattern' => is_string($value) && preg_match($constraint, $value),
            'in' => is_array($constraint) && in_array($value, $constraint, true),
            'not_empty' => !empty($value),
            default => true,
        };
    }

    /**
     * 获取默认值或提供的值
     */
    public function getValue(mixed $providedValue = null): mixed
    {
        if ($providedValue !== null) {
            if (!$this->validateValue($providedValue)) {
                throw new \InvalidArgumentException(
                    "变量 '{$this->name}' 的值不符合要求"
                );
            }
            return $providedValue;
        }

        if ($this->required && $this->defaultValue === null) {
            throw new \InvalidArgumentException(
                "变量 '{$this->name}' 是必需的"
            );
        }

        return $this->defaultValue;
    }

    /**
     * 转换为数组（用于存储）
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'default_value' => $this->defaultValue,
            'required' => $this->required,
            'validation_rules' => $this->validationRules,
            'description' => $this->description,
            'example' => $this->example,
        ];
    }

    /**
     * 从数组创建实例
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            type: $data['type'] ?? 'string',
            defaultValue: $data['default_value'] ?? null,
            required: $data['required'] ?? false,
            validationRules: $data['validation_rules'] ?? [],
            description: $data['description'] ?? '',
            example: $data['example'] ?? null
        );
    }
}
