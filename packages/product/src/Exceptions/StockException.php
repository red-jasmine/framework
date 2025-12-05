<?php

namespace RedJasmine\Product\Exceptions;


use RedJasmine\Support\Exceptions\BaseException;

class StockException extends BaseException
{


    public const SKU_FORBID_SALE = 202101;// SKU 禁售
}
