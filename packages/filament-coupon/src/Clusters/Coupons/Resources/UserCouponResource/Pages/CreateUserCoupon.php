<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CreateUserCoupon extends CreateRecord
{
    use ResourcePageHelper;

    protected static string $resource = UserCouponResource::class;
} 