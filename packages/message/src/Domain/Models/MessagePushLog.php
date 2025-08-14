<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Message\Domain\Models\Enums\PushChannelEnum;
use RedJasmine\Message\Domain\Models\Enums\PushStatusEnum;
use RedJasmine\Message\Domain\Models\ValueObjects\ErrorInfo;
use RedJasmine\Message\Domain\Models\ValueObjects\PushResult;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

/**
 * 推送日志聚合根
 */
class MessagePushLog extends Model
{
    use HasDateTimeFormatter;

    protected $fillable = [
        'message_id',
        'channel',
        'status',
        'pushed_at',
        'error_message',
        'retry_count',
        'response_data',
        'external_id',
        'response_time',
    ];

    protected $casts = [
        'channel' => PushChannelEnum::class,
        'status' => PushStatusEnum::class,
        'pushed_at' => 'datetime',
        'retry_count' => 'integer',
        'response_data' => 'array',
        'response_time' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        // 推送日志创建时的业务规则
        static::creating(function (self $log) {
            $log->validateCreation();
        });

        // 推送日志更新时的业务规则
        static::updating(function (self $log) {
            $log->validateUpdate();
        });
    }

    /**
     * 消息关联
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    /**
     * 获取推送结果值对象
     */
    public function getPushResult(): PushResult
    {
        return new PushResult(
            status: $this->status,
            responseTime: $this->response_time ?? 0,
            responseData: $this->response_data ?? [],
            pushedAt: $this->pushed_at,
            externalId: $this->external_id
        );
    }

    /**
     * 获取错误信息值对象
     */
    public function getErrorInfo(): ?ErrorInfo
    {
        if (empty($this->error_message)) {
            return null;
        }

        $responseData = $this->response_data ?? [];
        
        return new ErrorInfo(
            errorCode: $responseData['error_code'] ?? 'PUSH_ERROR',
            errorMessage: $this->error_message,
            errorDetails: $responseData,
            errorTime: $this->updated_at,
            errorLevel: $this->determineErrorLevel(),
            recoverable: $this->isRecoverable()
        );
    }

    /**
     * 记录推送成功
     */
    public function recordSuccess(
        int $responseTime = 0,
        array $responseData = [],
        ?string $externalId = null
    ): void {
        $this->status = PushStatusEnum::SENT;
        $this->pushed_at = now();
        $this->response_time = $responseTime;
        $this->response_data = $responseData;
        $this->external_id = $externalId;
        $this->error_message = null;

        $this->save();

        // 发布推送成功事件
        $this->dispatchPushSuccessEvent();
    }

    /**
     * 记录推送失败
     */
    public function recordFailure(
        string $errorMessage,
        ?string $errorCode = null,
        int $responseTime = 0,
        array $additionalData = []
    ): void {
        $this->status = PushStatusEnum::FAILED;
        $this->pushed_at = now();
        $this->error_message = $errorMessage;
        $this->response_time = $responseTime;
        
        $this->response_data = array_merge($additionalData, [
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
        ]);

        $this->save();

        // 发布推送失败事件
        $this->dispatchPushFailureEvent();
    }

    /**
     * 增加重试次数
     */
    public function incrementRetryCount(): void
    {
        $this->retry_count++;
        $this->save();
    }

    /**
     * 是否可以重试
     */
    public function canRetry(int $maxRetries = 3): bool
    {
        return $this->status->canRetry() && $this->retry_count < $maxRetries;
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
     * 获取推送耗时（毫秒）
     */
    public function getResponseTime(): int
    {
        return $this->response_time ?? 0;
    }

    /**
     * 确定错误级别
     */
    protected function determineErrorLevel(): string
    {
        $responseData = $this->response_data ?? [];
        $errorCode = $responseData['error_code'] ?? '';

        // 根据错误码确定错误级别
        return match (true) {
            str_contains($errorCode, 'AUTH') => 'critical',
            str_contains($errorCode, 'CONFIG') => 'critical',
            str_contains($errorCode, 'NETWORK') => 'high',
            str_contains($errorCode, 'TIMEOUT') => 'medium',
            default => 'medium',
        };
    }

    /**
     * 是否可恢复
     */
    protected function isRecoverable(): bool
    {
        $responseData = $this->response_data ?? [];
        $errorCode = $responseData['error_code'] ?? '';

        // 根据错误码确定是否可恢复
        return match (true) {
            str_contains($errorCode, 'AUTH') => false,
            str_contains($errorCode, 'CONFIG') => false,
            str_contains($errorCode, 'INVALID') => false,
            default => true,
        };
    }

    /**
     * 验证推送日志创建
     */
    protected function validateCreation(): void
    {
        if (empty($this->message_id)) {
            throw new \InvalidArgumentException('消息ID不能为空');
        }

        if (!$this->channel) {
            throw new \InvalidArgumentException('推送渠道不能为空');
        }
    }

    /**
     * 验证推送日志更新
     */
    protected function validateUpdate(): void
    {
        // 推送日志一旦创建，消息ID和渠道不能修改
        if ($this->isDirty(['message_id', 'channel']) && $this->exists) {
            throw new \InvalidArgumentException('推送日志的消息ID和渠道不能修改');
        }
    }

    /**
     * 发布推送成功事件
     */
    protected function dispatchPushSuccessEvent(): void
    {
        // 这里可以发布领域事件
        // event(new MessagePushSuccessEvent($this));
    }

    /**
     * 发布推送失败事件
     */
    protected function dispatchPushFailureEvent(): void
    {
        // 这里可以发布领域事件
        // event(new MessagePushFailureEvent($this));
    }

    /**
     * 查询作用域：按消息查询
     */
    public function scopeForMessage($query, string $messageId)
    {
        return $query->where('message_id', $messageId);
    }

    /**
     * 查询作用域：按渠道查询
     */
    public function scopeForChannel($query, PushChannelEnum $channel)
    {
        return $query->where('channel', $channel);
    }

    /**
     * 查询作用域：按状态查询
     */
    public function scopeWithStatus($query, PushStatusEnum $status)
    {
        return $query->where('status', $status);
    }

    /**
     * 查询作用域：成功的推送
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', PushStatusEnum::SENT);
    }

    /**
     * 查询作用域：失败的推送
     */
    public function scopeFailed($query)
    {
        return $query->where('status', PushStatusEnum::FAILED);
    }

    /**
     * 查询作用域：可重试的推送
     */
    public function scopeCanRetry($query, int $maxRetries = 3)
    {
        return $query->where('status', PushStatusEnum::FAILED)
                    ->where('retry_count', '<', $maxRetries);
    }

    /**
     * 查询作用域：按时间范围查询
     */
    public function scopeInTimeRange($query, \DateTimeInterface $start, \DateTimeInterface $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }
}
