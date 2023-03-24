<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: shook Liu  |  Email:24147287@qq.com  | Time: 2018/8/29/029 13:52
// +----------------------------------------------------------------------
// | TITLE: todo?
// +----------------------------------------------------------------------

namespace RedJasmine\Support\Exceptions;


use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * 业务异常
 */
abstract class AbstractException extends Exception implements HttpExceptionInterface
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


    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    public function getHeaders() : array
    {
        return $this->headers;
    }


    public function getData() : mixed
    {
        return $this->data;
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
