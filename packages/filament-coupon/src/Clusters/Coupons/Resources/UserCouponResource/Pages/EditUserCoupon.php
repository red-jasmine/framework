<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class EditUserCoupon extends EditRecord
{
    use ResourcePageHelper;

    protected static string $resource = UserCouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
} 