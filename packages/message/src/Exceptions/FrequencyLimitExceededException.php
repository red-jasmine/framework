<?php

declare(strict_types=1);

namespace RedJasmine\Message\Exceptions;

/**
 * 频率限制超限异常
 */
class FrequencyLimitExceededException extends MessageException
{
    public function __construct(
        string $receiverId,
        string $biz,
        string $period,
        int $maxCount,
        int $currentCount,
        int $code = 429,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: "超过频率限制: {$period}内最多发送{$maxCount}条消息，当前已发送{$currentCount}条",
            errorCode: 'FREQUENCY_LIMIT_EXCEEDED',
            errorDetails: [
                'receiver_id' => $receiverId,
                'biz' => $biz,
                'period' => $period,
                'max_count' => $maxCount,
                'current_count' => $currentCount,
                'retry_after' => $this->calculateRetryAfter($period),
            ],
            code: $code,
            previous: $previous
        );
    }

    /**
     * 创建小时频率限制异常
     */
    public static function hourly(string $receiverId, string $biz, int $maxCount, int $currentCount): self
    {
        return new self($receiverId, $biz, '1小时', $maxCount, $currentCount);
    }

    /**
     * 创建日频率限制异常
     */
    public static function daily(string $receiverId, string $biz, int $maxCount, int $currentCount): self
    {
        return new self($receiverId, $biz, '1天', $maxCount, $currentCount);
    }

    /**
     * 创建周频率限制异常
     */
    public static function weekly(string $receiverId, string $biz, int $maxCount, int $currentCount): self
    {
        return new self($receiverId, $biz, '1周', $maxCount, $currentCount);
    }

    /**
     * 创建月频率限制异常
     */
    public static function monthly(string $receiverId, string $biz, int $maxCount, int $currentCount): self
    {
        return new self($receiverId, $biz, '1月', $maxCount, $currentCount);
    }

    /**
     * 计算重试时间
     */
    protected function calculateRetryAfter(string $period): int
    {
        return match ($period) {
            '1小时' => 3600,
            '1天' => 86400,
            '1周' => 604800,
            '1月' => 2592000,
            default => 3600,
        };
    }

    /**
     * 获取重试时间
     */
    public function getRetryAfter(): int
    {
        return $this->errorDetails['retry_after'] ?? 3600;
    }
}
