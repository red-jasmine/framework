<?php

declare(strict_types=1);

namespace RedJasmine\Message\Exceptions;

/**
 * 推送服务异常
 */
class PushServiceException extends MessageException
{
    public function __construct(
        string $channel,
        string $message = '推送服务异常',
        array $errorDetails = [],
        int $code = 500,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: $message,
            errorCode: 'PUSH_SERVICE_ERROR',
            errorDetails: array_merge($errorDetails, ['channel' => $channel]),
            code: $code,
            previous: $previous
        );
    }

    /**
     * 创建推送失败异常
     */
    public static function pushFailed(string $channel, string $reason, array $details = []): self
    {
        return new self(
            channel: $channel,
            message: "推送失败: {$reason}",
            errorDetails: array_merge($details, ['reason' => $reason])
        );
    }

    /**
     * 创建认证失败异常
     */
    public static function authenticationFailed(string $channel): self
    {
        return new self(
            channel: $channel,
            message: '推送服务认证失败',
            errorDetails: ['reason' => 'authentication_failed'],
            code: 401
        );
    }

    /**
     * 创建配置错误异常
     */
    public static function configurationError(string $channel, string $configKey): self
    {
        return new self(
            channel: $channel,
            message: "推送服务配置错误: {$configKey}",
            errorDetails: ['config_key' => $configKey, 'reason' => 'configuration_error']
        );
    }

    /**
     * 创建网络错误异常
     */
    public static function networkError(string $channel, string $error): self
    {
        return new self(
            channel: $channel,
            message: "网络连接错误: {$error}",
            errorDetails: ['network_error' => $error, 'reason' => 'network_error']
        );
    }

    /**
     * 创建超时异常
     */
    public static function timeout(string $channel, int $timeoutSeconds): self
    {
        return new self(
            channel: $channel,
            message: "推送超时: {$timeoutSeconds}秒",
            errorDetails: ['timeout_seconds' => $timeoutSeconds, 'reason' => 'timeout']
        );
    }

    /**
     * 创建服务不可用异常
     */
    public static function serviceUnavailable(string $channel): self
    {
        return new self(
            channel: $channel,
            message: '推送服务不可用',
            errorDetails: ['reason' => 'service_unavailable'],
            code: 503
        );
    }

    /**
     * 创建频率限制异常
     */
    public static function rateLimited(string $channel, int $retryAfter = 60): self
    {
        return new self(
            channel: $channel,
            message: '推送频率受限',
            errorDetails: ['retry_after' => $retryAfter, 'reason' => 'rate_limited'],
            code: 429
        );
    }

    /**
     * 创建无效参数异常
     */
    public static function invalidParameters(string $channel, array $invalidParams): self
    {
        return new self(
            channel: $channel,
            message: '推送参数无效: ' . implode(', ', $invalidParams),
            errorDetails: ['invalid_parameters' => $invalidParams, 'reason' => 'invalid_parameters'],
            code: 400
        );
    }

    /**
     * 获取推送渠道
     */
    public function getChannel(): string
    {
        return $this->errorDetails['channel'];
    }

    /**
     * 获取失败原因
     */
    public function getReason(): ?string
    {
        return $this->errorDetails['reason'] ?? null;
    }
}
