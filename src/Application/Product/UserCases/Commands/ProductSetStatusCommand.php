<?php

namespace RedJasmine\Product\Application\Product\UserCases\Commands;

use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Application\Command;

class ProductSetStatusCommand extends Command
{

    public int $id;


    public ProductStatusEnum $status;

}
