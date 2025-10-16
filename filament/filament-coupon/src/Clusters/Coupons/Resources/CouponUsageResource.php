<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponUsageResource\Pages\ListCouponUsages;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponUsageResource\Pages\ViewCouponUsage;
use Filament\Forms;
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
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $cluster = Coupons::class;
    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __('red-jasmine-coupon::coupon_usage.labels.coupon_usage');
    }

    public static function getPluralModelLabel(): string
    {
        return __('red-jasmine-coupon::coupon_usage.labels.coupon_usage');
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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        ...static::ownerFormSchemas(),
                        
                        Select::make('coupon_id')
                            ->label(__('red-jasmine-coupon::coupon_usage.fields.coupon_id'))
                            ->relationship('coupon', 'name')
                            ->searchable()
                            ->preload()
                            ->disabled(),
                            
                        TextInput::make('user_id')
                            ->label(__('red-jasmine-coupon::coupon_usage.fields.user_id'))
                            ->numeric()
                            ->disabled(),
                            
                        TextInput::make('order_no')
                            ->label(__('red-jasmine-coupon::coupon_usage.fields.order_no'))
                            ->disabled(),
                            
                        TextInput::make('threshold_amount')
                            ->label(__('red-jasmine-coupon::coupon_usage.fields.threshold_amount'))
                            ->numeric()
                            ->prefix('¥')
                            ->disabled(),
                            
                        TextInput::make('discount_amount')
                            ->label(__('red-jasmine-coupon::coupon_usage.fields.discount_amount'))
                            ->numeric()
                            ->prefix('¥')
                            ->disabled(),
                            
                        TextInput::make('final_discount_amount')
                            ->label(__('red-jasmine-coupon::coupon_usage.fields.final_discount_amount'))
                            ->numeric()
                            ->prefix('¥')
                            ->disabled(),
                            
                        DateTimePicker::make('used_at')
                            ->label(__('red-jasmine-coupon::coupon_usage.fields.used_at'))
                            ->disabled(),
                            
                        TextInput::make('cost_bearer_type')
                            ->label(__('red-jasmine-coupon::coupon_usage.fields.cost_bearer'))
                            ->disabled(),
                            
                        TextInput::make('cost_bearer_id')
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
                TextColumn::make('id')
                    ->label(__('red-jasmine-coupon::coupon_usage.fields.id'))
                    ->sortable()
                    ->copyable(),
                    
                ...static::ownerTableColumns(),
                
                TextColumn::make('coupon.name')
                    ->label(__('red-jasmine-coupon::coupon_usage.fields.coupon_name'))
                    ,
                    
                TextColumn::make('user_id')
                    ->label(__('red-jasmine-coupon::coupon_usage.fields.user_id'))

                    ->copyable(),
                    
                TextColumn::make('order_no')
                    ->label(__('red-jasmine-coupon::coupon_usage.fields.order_no'))
                    ->copyable(),
                    

                TextColumn::make('discount_amount')
                    ->label(__('red-jasmine-coupon::coupon_usage.fields.discount_amount'))
                ,

                TextColumn::make('used_at')
                    ->label(__('red-jasmine-coupon::coupon_usage.fields.used_at'))
                    ->dateTime()
                    ,
                    
                TextColumn::make('cost_bearer_type')
                    ->label(__('red-jasmine-coupon::coupon_usage.fields.cost_bearer'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('cost_bearer_id')
                    ->label('成本承担方ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                ...static::operateTableColumns(),
            ])
            ->filters([
                SelectFilter::make('coupon_id')
                    ->label(__('red-jasmine-coupon::coupon_usage.fields.coupon_id'))
                    ->relationship('coupon', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                    
                Filter::make('user_id')
                    ->label(__('red-jasmine-coupon::coupon_usage.filters.user_id'))
                    ->schema([
                        TextInput::make('user_id')
                            ->label(__('red-jasmine-coupon::coupon_usage.fields.user_id'))
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['user_id'],
                            fn ($query, $userId) => $query->where('user_id', $userId)
                        );
                    }),
                    
                Filter::make('order_no')
                    ->label(__('red-jasmine-coupon::coupon_usage.fields.order_no'))
                    ->schema([
                        TextInput::make('order_no')
                            ->label(__('red-jasmine-coupon::coupon_usage.fields.order_no')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['order_no'],
                            fn ($query, $orderNo) => $query->where('order_no', 'like', '%' . $orderNo . '%')
                        );
                    }),
                    
                Filter::make('used_at')
                    ->label(__('red-jasmine-coupon::coupon_usage.filters.date_range'))
                    ->schema([
                        DatePicker::make('used_from')
                            ->label('使用时间从'),
                        DatePicker::make('used_until')
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
                    
                Filter::make('amount_range')
                    ->label('金额范围')
                    ->schema([
                        TextInput::make('min_amount')
                            ->label('最小金额')
                            ->numeric()
                            ->prefix('¥'),
                        TextInput::make('max_amount')
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
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('export')
                        ->label(__('red-jasmine-coupon::coupon_usage.commands.export'))
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
            'index' => ListCouponUsages::route('/'),
            'view' => ViewCouponUsage::route('/{record}'),
        ];
    }
} 