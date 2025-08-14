<?php

declare(strict_types=1);

namespace RedJasmine\Message\Exceptions;

/**
 * 消息访问被拒绝异常
 */
class MessageAccessDeniedException extends MessageException
{
    public function __construct(
        string $messageId,
        string $userId,
        string $reason = '没有权限访问此消息',
        int $code = 403,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: $reason,
            errorCode: 'MESSAGE_ACCESS_DENIED',
            errorDetails: [
                'message_id' => $messageId,
                'user_id' => $userId,
                'reason' => $reason,
            ],
            code: $code,
            previous: $previous
        );
    }

    /**
     * 创建无权限访问异常
     */
    public static function noPermission(string $messageId, string $userId): self
    {
        return new self($messageId, $userId, '没有权限访问此消息');
    }

    /**
     * 创建非消息接收人异常
     */
    public static function notReceiver(string $messageId, string $userId): self
    {
        return new self($messageId, $userId, '只有消息接收人才能访问此消息');
    }

    /**
     * 创建消息已过期异常
     */
    public static function expired(string $messageId, string $userId): self
    {
        return new self($messageId, $userId, '消息已过期，无法访问');
    }

    /**
     * 创建消息已归档异常
     */
    public static function archived(string $messageId, string $userId): self
    {
        return new self($messageId, $userId, '消息已归档，无法访问');
    }
}
