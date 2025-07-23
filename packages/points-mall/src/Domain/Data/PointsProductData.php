<?php

namespace RedJasmine\PointsMall\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class PointsProductData extends Data
{
    public UserInterface $owner;
    public string        $title;
    public ?string       $description = null;
    public ?string       $image       = null;


    #[WithCast(EnumCast::class, PointsProductPaymentModeEnum::class)]
    public PointsProductPaymentModeEnum $paymentMode = PointsProductPaymentModeEnum::POINTS_ONLY;

    public int    $point = 0;
    public ?Money $price = null;


    public int $stock         = 0;
    public int $lockStock     = 0;
    public int $safetyStock   = 0;
    public int $exchangeLimit = 0;

    #[WithCast(EnumCast::class, PointsProductStatusEnum::class)]
    public PointsProductStatusEnum $status = PointsProductStatusEnum::DRAFT;

    public int    $sort        = 0;
    public ?int   $categoryId  = null;
    public string $productType = 'product';
    public string $productId;
} 