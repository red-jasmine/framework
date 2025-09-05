<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class EditCoupon extends EditRecord
{
    use ResourcePageHelper;

    protected static string $resource = CouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
} 