<?php

namespace RedJasmine\Invitation\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

/**
 * 邀请码异常
 */
class InvitationCodeException extends AbstractException
{
    /**
     * 错误代码
     */
    public const int CODE = 40001;

    /**
     * 错误消息
     */
    public const string MESSAGE = '邀请码操作失败';

    /**
     * 构造函数
     */
    public function __construct(string $message = self::MESSAGE, int $code = self::CODE, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
} 