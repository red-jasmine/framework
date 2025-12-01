<?php

namespace RedJasmine\Product\Application\Attribute\Services\Commands;

use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeValueData;

class ProductAttributeValueUpdateCommand extends ProductAttributeValueData
{
    public int $id;
}
