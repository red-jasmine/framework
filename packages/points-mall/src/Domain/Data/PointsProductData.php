<?php

namespace RedJasmine\PointsMall\Domain\Data;

use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class PointsProductData extends Data
{
    public UserInterface $owner;
    public string $title;
    public ?string $description = null;
    public ?string $image = null;
    public int $point = 0;
    public string $price_currency = 'CNY';
    public float $price_amount = 0.0;
    
    #[WithCast(EnumCast::class, PointsProductPaymentModeEnum::class)]
    public PointsProductPaymentModeEnum $payment_mode = PointsProductPaymentModeEnum::POINTS_ONLY;
    
    public int $stock = 0;
    public int $lock_stock = 0;
    public int $safety_stock = 0;
    public int $exchange_limit = 0;
    
    #[WithCast(EnumCast::class, PointsProductStatusEnum::class)]
    public PointsProductStatusEnum $status = PointsProductStatusEnum::DRAFT;
    
    public int $sort = 0;
    public ?int $category_id = null;
    public ?string $product_type = null;
    public ?int $product_id = null;
} 