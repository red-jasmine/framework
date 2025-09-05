<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CreateCoupon extends CreateRecord
{
    use ResourcePageHelper;

    protected static string $resource = CouponResource::class;
} 