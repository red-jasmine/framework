<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\Coupon\Application\Services\CouponUsage\CouponUsageApplicationService;
use RedJasmine\Coupon\Domain\Data\CouponUsageData;
use RedJasmine\Coupon\Domain\Models\CouponUsage;
use RedJasmine\FilamentCoupon\Clusters\Coupons;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponUsageResource\Pages;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CouponUsageResource extends Resource
{
    use ResourcePageHelper;

    /**
     * @var class-string<CouponUsageApplicationService>
     */
    protected static string $service = CouponUsageApplicationService::class;
    protected static ?string $createCommand = CouponUsageData::class;
    protected static ?string $updateCommand = CouponUsageData::class;
    protected static bool $onlyOwner = true;

    protected static ?string $model = CouponUsage::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $cluster = Coupons::class;
    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __('red-jasmine-filament-coupon::coupon.labels.coupon_usage');
    }

    public static function getPluralModelLabel(): string
    {
        return __('red-jasmine-filament-coupon::coupon.labels.coupon_usages');
    }

    public static function canCreate(): bool
    {
        return false; // 使用记录通常不允许手动创建
    }

    public static function canEdit($record): bool
    {
        return false; // 使用记录通常不允许编辑
    }

    public static function canDelete($record): bool
    {
        return false; // 使用记录通常不允许删除
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        ...static::ownerFormSchemas(),
                        
                        Forms\Components\Select::make('coupon_id')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.coupon_id'))
                            ->relationship('coupon', 'name')
                            ->searchable()
                            ->preload()
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('user_id')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.user_id'))
                            ->numeric()
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('order_no')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.order_no'))
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('threshold_amount')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.threshold_amount'))
                            ->numeric()
                            ->prefix('¥')
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('discount_amount')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.discount_amount'))
                            ->numeric()
                            ->prefix('¥')
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('final_discount_amount')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.final_discount_amount'))
                            ->numeric()
                            ->prefix('¥')
                            ->disabled(),
                            
                        Forms\Components\DateTimePicker::make('used_at')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.used_at'))
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('cost_bearer_type')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.cost_bearer'))
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('cost_bearer_id')
                            ->label('成本承担方ID')
                            ->disabled(),
                    ])
                    ->columns(2),
                    
                ...static::operateFormSchemas(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.id'))
                    ->sortable()
                    ->copyable(),
                    
                ...static::ownerTableColumns(),
                
                Tables\Columns\TextColumn::make('coupon.name')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.coupon_name'))
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user_id')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.user_id'))
                    ->sortable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('order_no')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.order_no'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('threshold_amount')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.threshold_amount'))
                    ->money('CNY')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('discount_amount')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.discount_amount'))
                    ->money('CNY')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('final_discount_amount')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.final_discount_amount'))
                    ->money('CNY')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('used_at')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.used_at'))
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('cost_bearer_type')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.cost_bearer'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('cost_bearer_id')
                    ->label('成本承担方ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                ...static::operateTableColumns(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('coupon_id')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.coupon_id'))
                    ->relationship('coupon', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                    
                Tables\Filters\Filter::make('user_id')
                    ->label(__('red-jasmine-filament-coupon::coupon.filters.user_id'))
                    ->form([
                        Forms\Components\TextInput::make('user_id')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.user_id'))
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['user_id'],
                            fn ($query, $userId) => $query->where('user_id', $userId)
                        );
                    }),
                    
                Tables\Filters\Filter::make('order_no')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.order_no'))
                    ->form([
                        Forms\Components\TextInput::make('order_no')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.order_no')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['order_no'],
                            fn ($query, $orderNo) => $query->where('order_no', 'like', '%' . $orderNo . '%')
                        );
                    }),
                    
                Tables\Filters\Filter::make('used_at')
                    ->label(__('red-jasmine-filament-coupon::coupon.filters.date_range'))
                    ->form([
                        Forms\Components\DatePicker::make('used_from')
                            ->label('使用时间从'),
                        Forms\Components\DatePicker::make('used_until')
                            ->label('使用时间至'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['used_from'],
                                fn ($query, $date) => $query->whereDate('used_at', '>=', $date)
                            )
                            ->when(
                                $data['used_until'],
                                fn ($query, $date) => $query->whereDate('used_at', '<=', $date)
                            );
                    }),
                    
                Tables\Filters\Filter::make('amount_range')
                    ->label('金额范围')
                    ->form([
                        Forms\Components\TextInput::make('min_amount')
                            ->label('最小金额')
                            ->numeric()
                            ->prefix('¥'),
                        Forms\Components\TextInput::make('max_amount')
                            ->label('最大金额')
                            ->numeric()
                            ->prefix('¥'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['min_amount'],
                                fn ($query, $amount) => $query->where('final_discount_amount', '>=', $amount)
                            )
                            ->when(
                                $data['max_amount'],
                                fn ($query, $amount) => $query->where('final_discount_amount', '<=', $amount)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\Action::make('export')
                        ->label(__('red-jasmine-filament-coupon::coupon.actions.export'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function ($records) {
                            // 导出逻辑
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCouponUsages::route('/'),
            'view' => Pages\ViewCouponUsage::route('/{record}'),
        ];
    }
} 