<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\ValueObjects;

use RedJasmine\Message\Domain\Models\Enums\PushStatusEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

/**
 * 推送结果值对象
 */
class PushResult extends ValueObject
{
    /**
     * 推送状态
     */
    public readonly PushStatusEnum $status;

    /**
     * 响应时间（毫秒）
     */
    public readonly int $responseTime;

    /**
     * 响应数据
     */
    public readonly array $responseData;

    /**
     * 推送时间
     */
    public readonly \DateTimeInterface $pushedAt;

    /**
     * 外部推送ID（第三方服务返回的ID）
     */
    public readonly ?string $externalId;

    public function __construct(
        PushStatusEnum $status,
        int $responseTime = 0,
        array $responseData = [],
        ?\DateTimeInterface $pushedAt = null,
        ?string $externalId = null
    ) {
        $this->status = $status;
        $this->responseTime = max(0, $responseTime);
        $this->responseData = $responseData;
        $this->pushedAt = $pushedAt ?? new \DateTimeImmutable();
        $this->externalId = $externalId;
    }

    /**
     * 是否推送成功
     */
    public function isSuccess(): bool
    {
        return $this->status === PushStatusEnum::SENT;
    }

    /**
     * 是否推送失败
     */
    public function isFailed(): bool
    {
        return $this->status === PushStatusEnum::FAILED;
    }

    /**
     * 是否等待中
     */
    public function isPending(): bool
    {
        return $this->status === PushStatusEnum::PENDING;
    }

    /**
     * 获取错误信息
     */
    public function getErrorMessage(): ?string
    {
        return $this->responseData['error_message'] ?? null;
    }

    /**
     * 获取错误码
     */
    public function getErrorCode(): ?string
    {
        return $this->responseData['error_code'] ?? null;
    }

    /**
     * 获取响应消息
     */
    public function getResponseMessage(): ?string
    {
        return $this->responseData['message'] ?? null;
    }

    /**
     * 是否可以重试
     */
    public function canRetry(): bool
    {
        return $this->status->canRetry();
    }

    /**
     * 创建成功结果
     */
    public static function success(
        int $responseTime = 0,
        array $responseData = [],
        ?string $externalId = null
    ): self {
        return new self(
            status: PushStatusEnum::SENT,
            responseTime: $responseTime,
            responseData: $responseData,
            externalId: $externalId
        );
    }

    /**
     * 创建失败结果
     */
    public static function failed(
        string $errorMessage,
        ?string $errorCode = null,
        int $responseTime = 0,
        array $additionalData = []
    ): self {
        $responseData = array_merge($additionalData, [
            'error_message' => $errorMessage,
            'error_code' => $errorCode,
        ]);

        return new self(
            status: PushStatusEnum::FAILED,
            responseTime: $responseTime,
            responseData: $responseData
        );
    }

    /**
     * 创建等待结果
     */
    public static function pending(array $responseData = []): self
    {
        return new self(
            status: PushStatusEnum::PENDING,
            responseData: $responseData
        );
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status->value,
            'response_time' => $this->responseTime,
            'response_data' => $this->responseData,
            'pushed_at' => $this->pushedAt->format('Y-m-d H:i:s'),
            'external_id' => $this->externalId,
        ];
    }
}
