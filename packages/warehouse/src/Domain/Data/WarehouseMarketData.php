<?php

namespace RedJasmine\Warehouse\Domain\Data;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class WarehouseMarketData extends Data
{
    /**
     * 市场代码
     */
    public string $market;

    /**
     * 门店代码
     */
    public string $store;

    /**
     * 是否启用
     */
    public bool $isActive = true;

    /**
     * 是否主要市场/门店
     */
    public bool $isPrimary = false;

    public static function rules(ValidationContext $context): array
    {
        return [
            'market' => ['required', 'string', 'max:32'],
            'store' => ['required', 'string', 'max:32'],
            'is_active' => ['sometimes', 'boolean'],
            'is_primary' => ['sometimes', 'boolean'],
        ];
    }
}

