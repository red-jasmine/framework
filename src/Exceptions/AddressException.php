<?php

namespace RedJasmine\Address\Exceptions;

use Liushoukun\LaravelProjectTools\Exceptions\AppRuntimeException;

class AddressException extends AppRuntimeException
{

    public const DOMAIN_CODE = 30; // 地址

    public function getDomainCode() : int
    {
        return self::DOMAIN_CODE;
    }

    public function getServiceCode() : int
    {
        return self::DOMAIN_CODE;
    }


}
