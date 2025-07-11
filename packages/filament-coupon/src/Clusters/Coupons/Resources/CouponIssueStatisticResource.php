<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\Coupon\Application\Services\CouponIssueStatistic\CouponIssueStatisticApplicationService;
use RedJasmine\Coupon\Domain\Data\CouponIssueStatisticData;
use RedJasmine\Coupon\Domain\Models\CouponIssueStatistic;
use RedJasmine\FilamentCoupon\Clusters\Coupons;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponIssueStatisticResource\Pages;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class CouponIssueStatisticResource extends Resource
{
    use ResourcePageHelper;

    /**
     * @var class-string<CouponIssueStatisticApplicationService>
     */
    protected static string $service = CouponIssueStatisticApplicationService::class;
    protected static ?string $createCommand = CouponIssueStatisticData::class;
    protected static ?string $updateCommand = CouponIssueStatisticData::class;
    protected static bool $onlyOwner = true;

    protected static ?string $model = CouponIssueStatistic::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $cluster = Coupons::class;
    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return __('red-jasmine-filament-coupon::coupon.labels.coupon_issue_statistic');
    }

    public static function getPluralModelLabel(): string
    {
        return __('red-jasmine-filament-coupon::coupon.labels.coupon_issue_statistics');
    }

    public static function canCreate(): bool
    {
        return false; // 统计数据通常不允许手动创建
    }

    public static function canEdit($record): bool
    {
        return false; // 统计数据通常不允许编辑
    }

    public static function canDelete($record): bool
    {
        return false; // 统计数据通常不允许删除
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
                            
                        Forms\Components\DatePicker::make('date')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.date'))
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('total_issued')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.total_issued'))
                            ->numeric()
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('total_used')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.total_used'))
                            ->numeric()
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('total_expired')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.total_expired'))
                            ->numeric()
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('total_cost')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.total_cost'))
                            ->numeric()
                            ->prefix('¥')
                            ->disabled(),
                            
                        Forms\Components\DateTimePicker::make('last_updated')
                            ->label(__('red-jasmine-filament-coupon::coupon.fields.last_updated'))
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
                    
                Tables\Columns\TextColumn::make('date')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.date'))
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total_issued')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.total_issued'))
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total_used')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.total_used'))
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total_expired')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.total_expired'))
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('available_count')
                    ->label('可用数量')
                    ->getStateUsing(fn ($record) => $record->getAvailableCount())
                    ->numeric()
                    ->sortable(false),
                    
                Tables\Columns\TextColumn::make('usage_rate')
                    ->label('使用率')
                    ->getStateUsing(fn ($record) => number_format($record->getUsageRate() * 100, 2) . '%')
                    ->sortable(false),
                    
                Tables\Columns\TextColumn::make('expired_rate')
                    ->label('过期率')
                    ->getStateUsing(fn ($record) => number_format($record->getExpiredRate() * 100, 2) . '%')
                    ->sortable(false),
                    
                Tables\Columns\TextColumn::make('total_cost')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.total_cost'))
                    ->money('CNY')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('average_cost')
                    ->label('平均成本')
                    ->getStateUsing(fn ($record) => '¥' . number_format($record->getAverageCost(), 2))
                    ->sortable(false),
                    
                Tables\Columns\TextColumn::make('last_updated')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.last_updated'))
                    ->dateTime()
                    ->sortable(),
                    
                ...static::operateTableColumns(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('coupon_id')
                    ->label(__('red-jasmine-filament-coupon::coupon.fields.coupon_id'))
                    ->relationship('coupon', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                    
                Tables\Filters\Filter::make('date')
                    ->label(__('red-jasmine-filament-coupon::coupon.filters.date_range'))
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('日期从'),
                        Forms\Components\DatePicker::make('date_until')
                            ->label('日期至'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn ($query, $date) => $query->where('date', '>=', $date)
                            )
                            ->when(
                                $data['date_until'],
                                fn ($query, $date) => $query->where('date', '<=', $date)
                            );
                    }),
                    
                Tables\Filters\Filter::make('issued_range')
                    ->label('发放量范围')
                    ->form([
                        Forms\Components\TextInput::make('min_issued')
                            ->label('最小发放量')
                            ->numeric(),
                        Forms\Components\TextInput::make('max_issued')
                            ->label('最大发放量')
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['min_issued'],
                                fn ($query, $count) => $query->where('total_issued', '>=', $count)
                            )
                            ->when(
                                $data['max_issued'],
                                fn ($query, $count) => $query->where('total_issued', '<=', $count)
                            );
                    }),
                    
                Tables\Filters\Filter::make('cost_range')
                    ->label('成本范围')
                    ->form([
                        Forms\Components\TextInput::make('min_cost')
                            ->label('最小成本')
                            ->numeric()
                            ->prefix('¥'),
                        Forms\Components\TextInput::make('max_cost')
                            ->label('最大成本')
                            ->numeric()
                            ->prefix('¥'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['min_cost'],
                                fn ($query, $cost) => $query->where('total_cost', '>=', $cost)
                            )
                            ->when(
                                $data['max_cost'],
                                fn ($query, $cost) => $query->where('total_cost', '<=', $cost)
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
            'index' => Pages\ListCouponIssueStatistics::route('/'),
            'view' => Pages\ViewCouponIssueStatistic::route('/{record}'),
        ];
    }
} 