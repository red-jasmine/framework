<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

/**
 * 错误信息值对象
 */
class ErrorInfo extends ValueObject
{
    /**
     * 错误码
     */
    public readonly string $errorCode;

    /**
     * 错误描述
     */
    public readonly string $errorMessage;

    /**
     * 错误详情
     */
    public readonly array $errorDetails;

    /**
     * 错误时间
     */
    public readonly \DateTimeInterface $errorTime;

    /**
     * 错误级别 (low, medium, high, critical)
     */
    public readonly string $errorLevel;

    /**
     * 是否可恢复
     */
    public readonly bool $recoverable;

    public function __construct(
        string $errorCode,
        string $errorMessage,
        array $errorDetails = [],
        ?\DateTimeInterface $errorTime = null,
        string $errorLevel = 'medium',
        bool $recoverable = true
    ) {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->errorDetails = $errorDetails;
        $this->errorTime = $errorTime ?? new \DateTimeImmutable();
        $this->errorLevel = $errorLevel;
        $this->recoverable = $recoverable;

        $this->validate();
    }

    /**
     * 验证错误信息
     */
    protected function validate(): void
    {
        if (empty($this->errorCode)) {
            throw new \InvalidArgumentException('错误码不能为空');
        }

        if (empty($this->errorMessage)) {
            throw new \InvalidArgumentException('错误描述不能为空');
        }

        $validLevels = ['low', 'medium', 'high', 'critical'];
        if (!in_array($this->errorLevel, $validLevels, true)) {
            throw new \InvalidArgumentException('无效的错误级别');
        }
    }

    /**
     * 是否为关键错误
     */
    public function isCritical(): bool
    {
        return $this->errorLevel === 'critical';
    }

    /**
     * 是否为高级别错误
     */
    public function isHigh(): bool
    {
        return $this->errorLevel === 'high';
    }

    /**
     * 是否需要立即处理
     */
    public function requiresImmediateAttention(): bool
    {
        return in_array($this->errorLevel, ['high', 'critical'], true);
    }

    /**
     * 获取格式化的错误信息
     */
    public function getFormattedError(): string
    {
        return sprintf(
            '[%s] %s: %s',
            $this->errorCode,
            strtoupper($this->errorLevel),
            $this->errorMessage
        );
    }

    /**
     * 获取错误详情中的特定信息
     */
    public function getDetail(string $key, mixed $default = null): mixed
    {
        return $this->errorDetails[$key] ?? $default;
    }

    /**
     * 是否包含特定详情
     */
    public function hasDetail(string $key): bool
    {
        return array_key_exists($key, $this->errorDetails);
    }

    /**
     * 创建网络错误
     */
    public static function networkError(string $message, array $details = []): self
    {
        return new self(
            errorCode: 'NETWORK_ERROR',
            errorMessage: $message,
            errorDetails: $details,
            errorLevel: 'high',
            recoverable: true
        );
    }

    /**
     * 创建认证错误
     */
    public static function authenticationError(string $message, array $details = []): self
    {
        return new self(
            errorCode: 'AUTH_ERROR',
            errorMessage: $message,
            errorDetails: $details,
            errorLevel: 'high',
            recoverable: false
        );
    }

    /**
     * 创建配置错误
     */
    public static function configError(string $message, array $details = []): self
    {
        return new self(
            errorCode: 'CONFIG_ERROR',
            errorMessage: $message,
            errorDetails: $details,
            errorLevel: 'critical',
            recoverable: false
        );
    }

    /**
     * 创建服务不可用错误
     */
    public static function serviceUnavailable(string $service, array $details = []): self
    {
        return new self(
            errorCode: 'SERVICE_UNAVAILABLE',
            errorMessage: "服务不可用: {$service}",
            errorDetails: $details,
            errorLevel: 'high',
            recoverable: true
        );
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'error_code' => $this->errorCode,
            'error_message' => $this->errorMessage,
            'error_details' => $this->errorDetails,
            'error_time' => $this->errorTime->format('Y-m-d H:i:s'),
            'error_level' => $this->errorLevel,
            'recoverable' => $this->recoverable,
        ];
    }
}
