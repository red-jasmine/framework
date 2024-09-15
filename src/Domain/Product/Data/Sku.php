<?php

namespace RedJasmine\Product\Domain\Product\Data;

use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Data\Data;

class Sku extends Data
{
    public string            $properties;
    public ?string           $propertiesName;
    public Amount            $price;
    public Amount            $marketPrice;
    public Amount            $costPrice;
    public ProductStatusEnum $status        = ProductStatusEnum::ON_SALE;
    public int               $stock         = 0;
    public int               $safetyStock   = 0;
    public ?string           $image         = null;
    public ?string           $barcode       = null;
    public ?string           $outerId       = null;
    public ?int              $supplierSkuId = null;
}
