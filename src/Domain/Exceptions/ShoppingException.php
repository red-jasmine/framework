<?php

namespace RedJasmine\Shopping\Domain\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

class ShoppingException extends AbstractException
{


    /**
     * 商品异常
     */
    public const  PRODUCT_ERROR = 210023;
    public const  PRODUCT_OFF_SHELF = 210026;

}
