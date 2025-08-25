<?php

namespace RedJasmine\Promotion\Domain\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class ShopRequirements extends ValueObject
{
    public ?array $shopLevels = null;
    public ?float $minScore = null;
    public ?int $minOrderCount = null;
    public ?float $minSalesAmount = null;
    public ?array $regions = null;
    public ?array $excludeShops = null;
    public ?int $maxParticipants = null;
    
    public function __construct(
        ?array $shopLevels = null,
        ?float $minScore = null,
        ?int $minOrderCount = null,
        ?float $minSalesAmount = null,
        ?array $regions = null,
        ?array $excludeShops = null,
        ?int $maxParticipants = null
    ) {
        $this->shopLevels = $shopLevels;
        $this->minScore = $minScore;
        $this->minOrderCount = $minOrderCount;
        $this->minSalesAmount = $minSalesAmount;
        $this->regions = $regions;
        $this->excludeShops = $excludeShops;
        $this->maxParticipants = $maxParticipants;
    }
    
    public function toArray(): array
    {
        return [
            'shop_levels' => $this->shopLevels,
            'min_score' => $this->minScore,
            'min_order_count' => $this->minOrderCount,
            'min_sales_amount' => $this->minSalesAmount,
            'regions' => $this->regions,
            'exclude_shops' => $this->excludeShops,
            'max_participants' => $this->maxParticipants,
        ];
    }
}