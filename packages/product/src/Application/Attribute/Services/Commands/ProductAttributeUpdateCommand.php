<?php

namespace RedJasmine\Product\Application\Attribute\Services\Commands;

use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeData;

class ProductAttributeUpdateCommand extends ProductAttributeData
{
    public int $id;
}
