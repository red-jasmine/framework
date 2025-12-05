<?php

namespace RedJasmine\Product\Application\Product\Services\Commands;

use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;

class ProductSetStatusCommand extends Data
{

    public int $id;


    public ProductStatusEnum $status;

}
