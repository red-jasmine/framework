<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ViewUserCoupon extends ViewRecord
{
    use ResourcePageHelper;

    protected static string $resource = UserCouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
} 