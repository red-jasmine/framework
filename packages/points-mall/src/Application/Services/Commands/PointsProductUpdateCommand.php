<?php

namespace RedJasmine\PointsMall\Application\Services\Commands;

use RedJasmine\PointsMall\Domain\Data\PointsProductData;

class PointsProductUpdateCommand extends PointsProductData
{
    public int $id;
    
    // 继承 PointsProductData，包含所有更新积分商品所需的数据
} 