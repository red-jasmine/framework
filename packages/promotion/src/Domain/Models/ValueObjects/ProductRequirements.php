<?php

namespace RedJasmine\Promotion\Domain\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class ProductRequirements extends ValueObject
{
    public ?array $categories      = null;
    public ?array $brands          = null;
    public ?array $shops           = null;
    public ?float $minPrice        = null;
    public ?float $maxPrice        = null;
    public ?int   $minStock        = null;
    public ?int   $minSales        = null;
    public ?array $excludeProducts = null;

    public function __construct(
        ?array $categories = null,
        ?array $brands = null,
        ?array $shops = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?int $minStock = null,
        ?int $minSales = null,
        ?array $excludeProducts = null
    ) {
        $this->categories      = $categories;
        $this->brands          = $brands;
        $this->shops           = $shops;
        $this->minPrice        = $minPrice;
        $this->maxPrice        = $maxPrice;
        $this->minStock        = $minStock;
        $this->minSales        = $minSales;
        $this->excludeProducts = $excludeProducts;
    }

    public function toArray() : array
    {
        return [
            'categories'       => $this->categories,
            'brands'           => $this->brands,
            'shops'            => $this->shops,
            'min_price'        => $this->minPrice,
            'max_price'        => $this->maxPrice,
            'min_stock'        => $this->minStock,
            'min_sales'        => $this->minSales,
            'exclude_products' => $this->excludeProducts,
        ];
    }
}