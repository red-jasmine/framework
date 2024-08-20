<?php

namespace RedJasmine\Shopping\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

class ShoppingException extends AbstractException
{


    /**
     * 商品异常
     */
    public const  int PRODUCT_ERROR            = 210023;
    public const  int PRODUCT_OFF_SHELF        = 210026;
    public const  int PRODUCT_SKU_NOT_MATCHING = 210027;


    protected static array $codes = [
        self::PRODUCT_ERROR            => '产品不存在',
        self::PRODUCT_OFF_SHELF        => '产品不可销售',
        self::PRODUCT_SKU_NOT_MATCHING => '产品规格不匹配',
    ];

}
