<?php

declare(strict_types=1);

namespace RedJasmine\Message\Exceptions;

/**
 * 消息基础异常类
 */
class MessageException extends \Exception
{
    /**
     * 错误码
     */
    protected string $errorCode;

    /**
     * 错误详情
     */
    protected array $errorDetails;

    public function __construct(
        string $message = '',
        string $errorCode = 'MESSAGE_ERROR',
        array $errorDetails = [],
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        
        $this->errorCode = $errorCode;
        $this->errorDetails = $errorDetails;
    }

    /**
     * 获取错误码
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * 获取错误详情
     */
    public function getErrorDetails(): array
    {
        return $this->errorDetails;
    }

    /**
     * 设置错误详情
     */
    public function setErrorDetails(array $details): self
    {
        $this->errorDetails = $details;
        return $this;
    }

    /**
     * 添加错误详情
     */
    public function addErrorDetail(string $key, mixed $value): self
    {
        $this->errorDetails[$key] = $value;
        return $this;
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'error_code' => $this->errorCode,
            'error_message' => $this->getMessage(),
            'error_details' => $this->errorDetails,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
        ];
    }
}
