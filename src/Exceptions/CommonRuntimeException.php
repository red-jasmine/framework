<?php


namespace RedJasmine\Support\Exceptions;


use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * 公共业务异常
 */
abstract class CommonRuntimeException extends RuntimeException implements HttpExceptionInterface
{

    // 通用错误码
    protected array $errors;
    protected int   $statusCode;
    protected array $headers;
    protected mixed $data;


    public function __construct(string $message = '', int $code = 999999, array $errors = [], int $statusCode = 400, array $headers = [], mixed $data = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $this->formatCode($code), $previous);
        $this->errors     = $errors;
        $this->headers    = $headers;
        $this->statusCode = $statusCode;
        $this->data       = $data;
        // 校验设置
    }

    public function formatCode(int $code) : int
    {
        // 6位
        return (int)str_pad((string)$code, 6, 0);
    }

    /**
     * @param array $errors
     *
     * @return CommonRuntimeException
     */
    public function setErrors(array $errors) : CommonRuntimeException
    {
        $this->errors = $errors;
        return $this;
    }

    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return CommonRuntimeException
     */
    public function setStatusCode(int $statusCode) : CommonRuntimeException
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getHeaders() : array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return CommonRuntimeException
     */
    public function setHeaders(array $headers) : CommonRuntimeException
    {
        $this->headers = $headers;
        return $this;
    }

    public function getData() : mixed
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return CommonRuntimeException
     */
    public function setData(mixed $data) : CommonRuntimeException
    {
        $this->data = $data;
        return $this;
    }

    public function errors() : ?array
    {
        return count($this->errors) > 0 ? $this->errors : null;
    }


    public function setMessage($message = '') : static
    {
        $this->message = $message;
        return $this;
    }


}
