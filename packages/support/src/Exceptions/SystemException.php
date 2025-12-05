<?php

namespace RedJasmine\Support\Exceptions;


class SystemException extends BaseException
{
    protected int $statusCode = 500;
}