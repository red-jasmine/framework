<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Coupon\Domain\Models\Enums\UserCouponStatusEnum;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ListUserCoupons extends ListRecords
{
    use ResourcePageHelper;

    protected static string $resource = UserCouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->label(__('red-jasmine-filament-coupon::coupon.tabs.all'))
                ->badge(static::getResource()::getEloquentQuery()->count()),

            'available' => Tab::make()
                ->label(__('red-jasmine-filament-coupon::coupon.tabs.available'))
                ->badge(static::getResource()::getEloquentQuery()->where('status', UserCouponStatusEnum::AVAILABLE)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', UserCouponStatusEnum::AVAILABLE)),

            'used' => Tab::make()
                ->label(__('red-jasmine-filament-coupon::coupon.tabs.used'))
                ->badge(static::getResource()::getEloquentQuery()->where('status', UserCouponStatusEnum::USED)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', UserCouponStatusEnum::USED)),

            'expired' => Tab::make()
                ->label(__('red-jasmine-filament-coupon::coupon.tabs.expired'))
                ->badge(static::getResource()::getEloquentQuery()->where('status', UserCouponStatusEnum::EXPIRED)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', UserCouponStatusEnum::EXPIRED)),
        ];
    }
} 