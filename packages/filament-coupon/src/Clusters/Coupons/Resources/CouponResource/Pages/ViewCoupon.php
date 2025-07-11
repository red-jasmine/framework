<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ViewCoupon extends ViewRecord
{
    use ResourcePageHelper;

    protected static string $resource = CouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
} 