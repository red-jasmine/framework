<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: shook Liu  |  Email:24147287@qq.com  | Time: 2018/8/29/029 13:52
// +----------------------------------------------------------------------
// | TITLE: todo?
// +----------------------------------------------------------------------

namespace  RedJasmine\Support\Exceptions;



use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * 业务异常
 * @const BUSINESS_CODE
 * @const  SERVICE_CODE
 * @property array $errorList 错误列表
 */
abstract class AppRuntimeException extends RuntimeException implements HttpExceptionInterface
{

    // 通用错误码

    protected array $errors;
    protected int   $statusCode;
    protected array $headers;
    protected mixed $data;


    public function __construct(string $message = "", int $code = 99, array $errors = [], int $statusCode = 400, array $headers = [], mixed $data = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $this->formatCode($code), $previous);
        $this->errors     = $errors;
        $this->headers    = $headers;
        $this->statusCode = $statusCode;
        $this->data       = $data;
        // 校验设置
        $this->validateCustomCodePrefix();
    }

    /**
     * 验证自定义代码签注
     * @return void
     */
    private function validateCustomCodePrefix() : void
    {
        $domainCode  = 10;
        $serviceCode = 99;
        $min         = 10;
        $max         = 99;
        if ($domainCode < $min || $domainCode > $max) {
            throw new RuntimeException('DOMAIN_CODE 必须在是两位数');
        }
        if ($serviceCode < $min || $serviceCode > $max) {
            throw new RuntimeException('SERVICE_CODE 必须在是两位数');
        }

    }

    public function formatCode(int $code) : int
    {
        $domainCode  = $this->getDomainCode();
        $serviceCode = $this->getServiceCode();
        return (int)($domainCode . $serviceCode . $code);
    }


    public function getDefaultMessage($code, $message = '')
    {
        if (filled($message)) {
            return $message;
        }
        return self::$errorList[$code] ?? ($this->message ?? '');
    }


    public function getStatusCode() : int
    {
        return $this->statusCode;
    }


    public function getErrors() : array
    {
        return count($this->errors) > 0 ? $this->errors : [];
    }

    public function getData() : mixed
    {
        return $this->data;
    }

    public function errors() : ?array
    {
        return count($this->errors) > 0 ? $this->errors : null;
    }

    public function getHeaders() : array
    {

        return $this->headers;
    }


    public function setMessage($message = '') : static
    {
        $this->message = $message;
        return $this;
    }


}
