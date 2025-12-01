<?php

namespace RedJasmine\Product\Application\Attribute\Services\Commands;

use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeGroupData;

class ProductAttributeGroupUpdateCommand extends ProductAttributeGroupData
{
    public int $id;
}
