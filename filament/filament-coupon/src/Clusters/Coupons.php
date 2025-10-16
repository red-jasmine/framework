<?php

namespace RedJasmine\FilamentCoupon\Clusters;

use Filament\Clusters\Cluster;

class Coupons extends Cluster
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return __('red-jasmine-filament-coupon::coupon.label');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return __('red-jasmine-filament-coupon::coupon.label');
    }
} 