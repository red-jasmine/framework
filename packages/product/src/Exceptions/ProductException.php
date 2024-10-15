<?php

namespace RedJasmine\Product\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

class ProductException extends AbstractException
{

    public const PRODUCT_FORBID_SALE  = 201101; // 禁止销售

    public const PRODUCT_MIN_LIMIT  = 201001;
    public const PRODUCT_MAX_LIMIT  = 201002;
    public const PRODUCT_STEP_LIMIT = 201003;


}
