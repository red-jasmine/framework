<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Actions;
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
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->label(__('red-jasmine-coupon::user_coupon.tabs.all'))
                ->badge(static::getResource()::getEloquentQuery()->count()),

            'available' => Tab::make()
                ->label(__('red-jasmine-coupon::user_coupon.tabs.available'))
                ->badge(static::getResource()::getEloquentQuery()->where('status', UserCouponStatusEnum::AVAILABLE)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', UserCouponStatusEnum::AVAILABLE)),

            'used' => Tab::make()
                ->label(__('red-jasmine-coupon::user_coupon.tabs.used'))
                ->badge(static::getResource()::getEloquentQuery()->where('status', UserCouponStatusEnum::USED)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', UserCouponStatusEnum::USED)),

            'expired' => Tab::make()
                ->label(__('red-jasmine-coupon::user_coupon.tabs.expired'))
                ->badge(static::getResource()::getEloquentQuery()->where('status', UserCouponStatusEnum::EXPIRED)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', UserCouponStatusEnum::EXPIRED)),
        ];
    }
} 