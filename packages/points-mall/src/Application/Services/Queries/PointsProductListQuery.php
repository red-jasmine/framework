<?php

namespace RedJasmine\PointsMall\Application\Services\Queries;

use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductStatusEnum;
use RedJasmine\Support\Application\Queries\PaginationQuery;

class PointsProductListQuery extends PaginationQuery
{
    public ?string $title = null;
    public ?int $category_id = null;
    public ?PointsProductStatusEnum $status = null;
    public ?PointsProductPaymentModeEnum $payment_mode = null;
    public ?int $min_point = null;
    public ?int $max_point = null;
    public ?float $min_price = null;
    public ?float $max_price = null;
    public ?string $product_type = null;
    public ?int $product_id = null;
} 