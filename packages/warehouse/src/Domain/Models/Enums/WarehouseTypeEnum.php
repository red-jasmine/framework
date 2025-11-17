<?php

namespace RedJasmine\Warehouse\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum WarehouseTypeEnum: string
{
    use EnumsHelper;

    case WAREHOUSE = 'warehouse'; // 仓库
    case STORE = 'store'; // 门店
    case DISTRIBUTION_CENTER = 'distribution_center'; // 配送中心

    public static function labels(): array
    {
        return [
            self::WAREHOUSE->value => '仓库',
            self::STORE->value => '门店',
            self::DISTRIBUTION_CENTER->value => '配送中心',
        ];
    }

    public static function colors(): array
    {
        return [
            self::WAREHOUSE->value => 'primary',
            self::STORE->value => 'success',
            self::DISTRIBUTION_CENTER->value => 'info',
        ];
    }

    public static function icons(): array
    {
        return [
            self::WAREHOUSE->value => 'heroicon-o-building-office',
            self::STORE->value => 'heroicon-o-shopping-bag',
            self::DISTRIBUTION_CENTER->value => 'heroicon-o-truck',
        ];
    }
}

