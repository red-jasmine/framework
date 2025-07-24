<?php

namespace RedJasmine\PointsMall\Application\Services\Queries;

use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductStatusEnum;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;


class PointsProductPaginationQuery extends PaginateQuery
{
    public ?string                       $title        = null;
    public ?int                          $categoryId   = null;
    public ?PointsProductStatusEnum      $status       = null;
    public ?PointsProductPaymentModeEnum $payment_mode = null;
    public ?string                       $productType  = null;
    public ?int                          $productId    = null;
} 