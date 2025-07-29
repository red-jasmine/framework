<?php

namespace RedJasmine\PointsMall\Domain\Data;

use RedJasmine\Ecommerce\Domain\Data\Address\AddressData;
use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;

class PointsExchangeOrderData extends PurchaseFactor
{

    public PointsProduct $pointsProduct;
    public ?string       $skuId    = null;
    public int           $quantity = 1;
    public ?AddressData  $address = null;

}