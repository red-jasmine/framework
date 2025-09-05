<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponUsageResource\Pages;

use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponUsageResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ListCouponUsages extends ListRecords
{
    use ResourcePageHelper;

    protected static string $resource = CouponUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 使用记录不支持手动创建
        ];
    }
} 