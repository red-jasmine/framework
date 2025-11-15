<?php

namespace RedJasmine\Product\Application\Price\Services\Commands;

use RedJasmine\Product\Domain\Price\Data\ProductPriceCommandData;

class ProductPriceUpdateCommand extends ProductPriceCommandData
{
    public int $id;
}

