<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponIssueStatisticResource\Pages;

use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponIssueStatisticResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ListCouponIssueStatistics extends ListRecords
{
    use ResourcePageHelper;

    protected static string $resource = CouponIssueStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 统计数据不支持手动创建
        ];
    }
} 