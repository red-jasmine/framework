<?php

declare(strict_types=1);

namespace RedJasmine\Message\Exceptions;

/**
 * 消息验证异常
 */
class MessageValidationException extends MessageException
{
    /**
     * 验证错误
     */
    protected array $validationErrors;

    public function __construct(
        string $message = '消息验证失败',
        array $validationErrors = [],
        array $errorDetails = [],
        int $code = 422,
        ?\Throwable $previous = null
    ) {
        $this->validationErrors = $validationErrors;
        
        parent::__construct(
            message: $message,
            errorCode: 'MESSAGE_VALIDATION_ERROR',
            errorDetails: array_merge($errorDetails, ['validation_errors' => $validationErrors]),
            code: $code,
            previous: $previous
        );
    }

    /**
     * 获取验证错误
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    /**
     * 添加验证错误
     */
    public function addValidationError(string $field, string $message): self
    {
        $this->validationErrors[$field][] = $message;
        $this->errorDetails['validation_errors'] = $this->validationErrors;
        return $this;
    }

    /**
     * 创建标题验证异常
     */
    public static function titleRequired(): self
    {
        return new self(
            message: '消息标题不能为空',
            validationErrors: ['title' => ['消息标题不能为空']]
        );
    }

    /**
     * 创建标题长度异常
     */
    public static function titleTooLong(int $maxLength = 255): self
    {
        return new self(
            message: "消息标题长度不能超过{$maxLength}个字符",
            validationErrors: ['title' => ["消息标题长度不能超过{$maxLength}个字符"]]
        );
    }

    /**
     * 创建内容验证异常
     */
    public static function contentRequired(): self
    {
        return new self(
            message: '消息内容不能为空',
            validationErrors: ['content' => ['消息内容不能为空']]
        );
    }

    /**
     * 创建接收人验证异常
     */
    public static function receiverRequired(): self
    {
        return new self(
            message: '接收人不能为空',
            validationErrors: ['receiver_id' => ['接收人不能为空']]
        );
    }

    /**
     * 创建过期时间验证异常
     */
    public static function invalidExpiresAt(): self
    {
        return new self(
            message: '过期时间不能早于当前时间',
            validationErrors: ['expires_at' => ['过期时间不能早于当前时间']]
        );
    }

    /**
     * 创建推送渠道验证异常
     */
    public static function invalidChannels(array $invalidChannels): self
    {
        return new self(
            message: '无效的推送渠道: ' . implode(', ', $invalidChannels),
            validationErrors: ['channels' => ['无效的推送渠道: ' . implode(', ', $invalidChannels)]]
        );
    }
}
