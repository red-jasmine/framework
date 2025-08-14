<?php

declare(strict_types=1);

namespace RedJasmine\Message\Exceptions;

/**
 * 消息存储异常
 */
class MessageStorageException extends MessageException
{
    public function __construct(
        string $operation,
        string $message = '消息存储异常',
        array $errorDetails = [],
        int $code = 500,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: $message,
            errorCode: 'MESSAGE_STORAGE_ERROR',
            errorDetails: array_merge($errorDetails, ['operation' => $operation]),
            code: $code,
            previous: $previous
        );
    }

    /**
     * 创建保存失败异常
     */
    public static function saveFailed(string $messageId, string $reason): self
    {
        return new self(
            operation: 'save',
            message: "消息保存失败: {$reason}",
            errorDetails: ['message_id' => $messageId, 'reason' => $reason]
        );
    }

    /**
     * 创建更新失败异常
     */
    public static function updateFailed(string $messageId, string $reason): self
    {
        return new self(
            operation: 'update',
            message: "消息更新失败: {$reason}",
            errorDetails: ['message_id' => $messageId, 'reason' => $reason]
        );
    }

    /**
     * 创建删除失败异常
     */
    public static function deleteFailed(string $messageId, string $reason): self
    {
        return new self(
            operation: 'delete',
            message: "消息删除失败: {$reason}",
            errorDetails: ['message_id' => $messageId, 'reason' => $reason]
        );
    }

    /**
     * 创建查询失败异常
     */
    public static function queryFailed(string $reason): self
    {
        return new self(
            operation: 'query',
            message: "消息查询失败: {$reason}",
            errorDetails: ['reason' => $reason]
        );
    }

    /**
     * 创建数据库连接异常
     */
    public static function databaseConnectionFailed(): self
    {
        return new self(
            operation: 'connection',
            message: '数据库连接失败',
            errorDetails: ['reason' => 'database_connection_failed']
        );
    }

    /**
     * 创建事务失败异常
     */
    public static function transactionFailed(string $reason): self
    {
        return new self(
            operation: 'transaction',
            message: "数据库事务失败: {$reason}",
            errorDetails: ['reason' => $reason]
        );
    }

    /**
     * 创建数据完整性异常
     */
    public static function dataIntegrityViolation(string $constraint): self
    {
        return new self(
            operation: 'integrity_check',
            message: "数据完整性约束违反: {$constraint}",
            errorDetails: ['constraint' => $constraint, 'reason' => 'data_integrity_violation']
        );
    }

    /**
     * 获取操作类型
     */
    public function getOperation(): string
    {
        return $this->errorDetails['operation'];
    }

    /**
     * 获取失败原因
     */
    public function getReason(): ?string
    {
        return $this->errorDetails['reason'] ?? null;
    }
}
