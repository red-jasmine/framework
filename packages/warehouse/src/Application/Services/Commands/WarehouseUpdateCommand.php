<?php

namespace RedJasmine\Warehouse\Application\Services\Commands;

use RedJasmine\Warehouse\Domain\Data\WarehouseData;

class WarehouseUpdateCommand extends WarehouseData
{
    /**
     * 仓库ID
     */
    public int $id;
}

