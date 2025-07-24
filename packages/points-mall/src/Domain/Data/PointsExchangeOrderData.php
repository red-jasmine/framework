<?php

namespace RedJasmine\PointsMall\Domain\Data;

use RedJasmine\PointsMall\Domain\Models\Enums\PointsExchangeOrderStatusEnum;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class PointsExchangeOrderData extends Data
{
    public UserInterface $owner;
    public string $orderNo;
    public string $outerOrderNo;
    public string $pointProductId;
    public string $productType;
    public string $productId;
    public string $productTitle;
    public int $point = 0;
    public string $priceCurrency = 'CNY';
    public float $priceAmount = 0.0;
    public int $quantity = 1;
    
    #[WithCast(EnumCast::class, PointsProductPaymentModeEnum::class)]
    public PointsProductPaymentModeEnum $paymentMode = PointsProductPaymentModeEnum::POINTS;
    
    public string $paymentStatus = 'pending';
    
    #[WithCast(EnumCast::class, PointsExchangeOrderStatusEnum::class)]
    public PointsExchangeOrderStatusEnum $status = PointsExchangeOrderStatusEnum::EXCHANGED;
    
    public ?string $exchangeTime = null;
} 