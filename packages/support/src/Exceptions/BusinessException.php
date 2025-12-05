<?php

namespace RedJasmine\Support\Exceptions;

/**
 * 业务异常
 */
class BusinessException extends BaseException
{
    protected int $statusCode = 400;

}
