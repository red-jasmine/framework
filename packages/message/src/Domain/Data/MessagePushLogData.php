<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Data;

use RedJasmine\Message\Domain\Models\Enums\PushChannelEnum;
use RedJasmine\Message\Domain\Models\Enums\PushStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 消息推送日志数据传输对象
 */
class MessagePushLogData extends Data
{
    public function __construct(
        public string $messageId,
        
        #[WithCast(EnumCast::class, PushChannelEnum::class)]
        public PushChannelEnum $channel,
        
        #[WithCast(EnumCast::class, PushStatusEnum::class)]
        public PushStatusEnum $status = PushStatusEnum::PENDING,
        
        public ?\DateTimeInterface $pushedAt = null,
        public ?string $errorMessage = null,
        public int $retryCount = 0,
        public ?array $responseData = null,
        public ?string $externalId = null,
        public int $responseTime = 0,
    ) {
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
     * 是否可以重试
     */
    public function canRetry(int $maxRetries = 3): bool
    {
        return $this->status->canRetry() && $this->retryCount < $maxRetries;
    }

    /**
     * 记录推送成功
     */
    public function recordSuccess(
        int $responseTime = 0,
        array $responseData = [],
        ?string $externalId = null
    ): self {
        $this->status = PushStatusEnum::SENT;
        $this->pushedAt = new \DateTimeImmutable();
        $this->responseTime = $responseTime;
        $this->responseData = $responseData;
        $this->externalId = $externalId;
        $this->errorMessage = null;

        return $this;
    }

    /**
     * 记录推送失败
     */
    public function recordFailure(
        string $errorMessage,
        ?string $errorCode = null,
        int $responseTime = 0,
        array $additionalData = []
    ): self {
        $this->status = PushStatusEnum::FAILED;
        $this->pushedAt = new \DateTimeImmutable();
        $this->errorMessage = $errorMessage;
        $this->responseTime = $responseTime;
        
        $this->responseData = array_merge($additionalData, [
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
        ]);

        return $this;
    }

    /**
     * 增加重试次数
     */
    public function incrementRetryCount(): self
    {
        $this->retryCount++;
        return $this;
    }

    /**
     * 获取错误信息
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage ?? $this->responseData['error_message'] ?? null;
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
     * 设置响应数据
     */
    public function setResponseData(string $key, mixed $value): self
    {
        if ($this->responseData === null) {
            $this->responseData = [];
        }

        $this->responseData[$key] = $value;

        return $this;
    }

    /**
     * 获取响应数据
     */
    public function getResponseData(string $key, mixed $default = null): mixed
    {
        return $this->responseData[$key] ?? $default;
    }

    /**
     * 合并响应数据
     */
    public function mergeResponseData(array $data): self
    {
        $this->responseData = array_merge($this->responseData ?? [], $data);

        return $this;
    }

    /**
     * 获取推送耗时（毫秒）
     */
    public function getResponseTime(): int
    {
        return $this->responseTime;
    }

    /**
     * 设置推送耗时
     */
    public function setResponseTime(int $responseTime): self
    {
        $this->responseTime = max(0, $responseTime);
        return $this;
    }

    /**
     * 是否为实时推送渠道
     */
    public function isRealtimeChannel(): bool
    {
        return $this->channel->isRealtime();
    }

    /**
     * 是否支持富文本
     */
    public function supportsRichText(): bool
    {
        return $this->channel->supportsRichText();
    }
}
