<?php

namespace RedJasmine\Product\Exceptions;


use RedJasmine\Support\Exceptions\AbstractException;

class StockException extends AbstractException
{


    public const SKU_FORBID_SALE = 202101;// SKU 禁售
}
