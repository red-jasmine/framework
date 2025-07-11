<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponUsageResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponUsageResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ViewCouponUsage extends ViewRecord
{
    use ResourcePageHelper;

    protected static string $resource = CouponUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 使用记录不支持编辑
        ];
    }
} 