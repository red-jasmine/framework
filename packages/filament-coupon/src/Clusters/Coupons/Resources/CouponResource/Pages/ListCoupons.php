<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ListCoupons extends ListRecords
{
    use ResourcePageHelper;

    protected static string $resource = CouponResource::class;

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

            'draft' => Tab::make()
                ->label(__('red-jasmine-filament-coupon::coupon.tabs.draft'))
                ->badge(static::getResource()::getEloquentQuery()->where('status', CouponStatusEnum::DRAFT)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CouponStatusEnum::DRAFT)),

            'published' => Tab::make()
                ->label(__('red-jasmine-filament-coupon::coupon.tabs.published'))
                ->badge(static::getResource()::getEloquentQuery()->where('status', CouponStatusEnum::PUBLISHED)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CouponStatusEnum::PUBLISHED)),

            'paused' => Tab::make()
                ->label(__('red-jasmine-filament-coupon::coupon.tabs.paused'))
                ->badge(static::getResource()::getEloquentQuery()->where('status', CouponStatusEnum::PAUSED)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CouponStatusEnum::PAUSED)),

            'expired' => Tab::make()
                ->label(__('red-jasmine-filament-coupon::coupon.tabs.expired'))
                ->badge(static::getResource()::getEloquentQuery()->where('status', CouponStatusEnum::EXPIRED)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', CouponStatusEnum::EXPIRED)),
        ];
    }
} 