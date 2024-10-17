<?php

namespace RedJasmine\Product\Domain\Service\Data;

use RedJasmine\Product\Domain\Service\Models\Enums\ServiceStatusEnum;
use RedJasmine\Support\Data\Data;

class ProductService extends Data
{


    public string            $name;
    public ?string           $description;
    public bool              $isShow  = false;
    public ServiceStatusEnum $status  = ServiceStatusEnum::ENABLE;
    public int               $sort    = 0;
    public ?string           $cluster = null;
    public ?string           $icon    = null;
    public ?string           $color   = null;


}
