<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Support\Carbon;
use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Domain\Data\CouponData;
use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountAmountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\DiscountLevelEnum;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCoupon\Clusters\Coupons;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource\Pages;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;

class CouponResource extends Resource
{
    use ResourcePageHelper;

    /**
     * @var class-string<CouponApplicationService>
     */
    protected static string  $service       = CouponApplicationService::class;
    protected static ?string $createCommand = CouponData::class;
    protected static ?string $updateCommand = CouponData::class;
    protected static bool    $onlyOwner     = true;

    protected static ?string $model          = Coupon::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $cluster        = Coupons::class;
    protected static ?int    $navigationSort = 1;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-coupon::coupon.labels.coupon');
    }

    public static function getPluralModelLabel() : string
    {
        return __('red-jasmine-coupon::coupon.labels.coupon');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('coupon_tabs')
                                     ->tabs([
                                         // 基础信息标签页
                                         Forms\Components\Tabs\Tab::make('basic_info')
                                                                  ->label(__('red-jasmine-coupon::coupon.labels.basic_info'))
                                                                  ->schema([
                                                                      Forms\Components\Section::make()
                                                                                              ->schema([
                                                                                                  ...static::ownerFormSchemas(),
                                                                                                  ...static::ownerFormSchemas('cost_bearer'),

                                                                                                  Forms\Components\TextInput::make('name')
                                                                                                                            ->label(__('red-jasmine-coupon::coupon.fields.name'))
                                                                                                                            ->required()
                                                                                                                            ->maxLength(255),

                                                                                                  Forms\Components\Textarea::make('description')
                                                                                                                           ->label(__('red-jasmine-coupon::coupon.fields.description'))
                                                                                                                           ->rows(3),

                                                                                                  Forms\Components\FileUpload::make('image')
                                                                                                                             ->label(__('red-jasmine-coupon::coupon.fields.image'))
                                                                                                                             ->image()
                                                                                                                             ->directory('coupons'),

                                                                                                  Forms\Components\Toggle::make('is_show')
                                                                                                                         ->label(__('red-jasmine-coupon::coupon.fields.is_show'))
                                                                                                                         ->default(true),

                                                                                                  Forms\Components\Select::make('status')
                                                                                                                         ->label(__('red-jasmine-coupon::coupon.fields.status'))
                                                                                                                         ->useEnum(CouponStatusEnum::class)
                                                                                                                         ->default(CouponStatusEnum::DRAFT)
                                                                                                                         ->required(),

                                                                                                  Forms\Components\TextInput::make('total_quantity')
                                                                                                                            ->label(__('red-jasmine-coupon::coupon.fields.total_quantity'))
                                                                                                                            ->numeric()
                                                                                                                            ->default(100)
                                                                                                                            ->required(),

                                                                                                  Forms\Components\TextInput::make('sort')
                                                                                                                            ->label(__('red-jasmine-coupon::coupon.fields.sort'))
                                                                                                                            ->numeric()
                                                                                                                            ->default(0),

                                                                                                  Forms\Components\Textarea::make('remarks')
                                                                                                                           ->label(__('red-jasmine-coupon::coupon.fields.remarks'))
                                                                                                                           ->rows(3),

                                                                                                  Forms\Components\DateTimePicker::make('start_time')
                                                                                                                                 ->default(Carbon::now()->startOfDay())
                                                                                                                                 ->label(__('red-jasmine-coupon::coupon.fields.start_time')),

                                                                                                  Forms\Components\DateTimePicker::make('end_time')
                                                                                                                                 ->default(Carbon::now()->addMonth()->endOfDay())
                                                                                                                                 ->label(__('red-jasmine-coupon::coupon.fields.end_time')),
                                                                                              ])
                                                                                              ->columns(2),
                                                                  ]),

                                         // 优惠设置标签页
                                         Forms\Components\Tabs\Tab::make('discount_settings')
                                                                  ->label(__('red-jasmine-coupon::coupon.labels.discount_settings'))
                                                                  ->schema([
                                                                      Forms\Components\Section::make()
                                                                                              ->schema([
                                                                                                  Forms\Components\Select::make('discount_level')
                                                                                                                         ->label(__('red-jasmine-coupon::coupon.fields.discount_level'))
                                                                                                                         ->useEnum(DiscountLevelEnum::class)
                                                                                                                         ->default(DiscountLevelEnum::ORDER)
                                                                                                                         ->required(),

                                                                                                  Forms\Components\Select::make('discount_amount_type')
                                                                                                                         ->label(__('red-jasmine-coupon::coupon.fields.discount_amount_type'))
                                                                                                                         ->useEnum(DiscountAmountTypeEnum::class)
                                                                                                                         ->default(DiscountAmountTypeEnum::PERCENTAGE)
                                                                                                                         ->required()
                                                                                                                         ->live(),


                                                                                                  Cluster::make(
                                                                                                      [
                                                                                                          Forms\Components\TextInput::make('threshold_value')
                                                                                                                                    ->label(__('red-jasmine-coupon::coupon.fields.threshold_value'))
                                                                                                                                    ->numeric()
                                                                                                                                    ->required()
                                                                                                                                    ->prefix('满')
                                                                                                                                    ->minValue(0),

                                                                                                          Forms\Components\Select::make('threshold_type')
                                                                                                                                 ->label(__('red-jasmine-coupon::coupon.fields.threshold_type'))
                                                                                                                                 ->useEnum(ThresholdTypeEnum::class)
                                                                                                                                 ->default(ThresholdTypeEnum::AMOUNT)
                                                                                                                                 ->required(),

                                                                                                          Forms\Components\TextInput::make('discount_amount_value')
                                                                                                                                    ->label(__('red-jasmine-coupon::coupon.fields.discount_amount_value'))
                                                                                                                                    ->numeric()
                                                                                                                                    ->required()
                                                                                                                                    ->minValue(0)
                                                                                                                                    ->prefix(fn(
                                                                                                                                        Forms\Get $get
                                                                                                                                    ) => $get('discount_amount_type') === DiscountAmountTypeEnum::PERCENTAGE ?'打':'减')
                                                                                                                                    ->suffix(fn(
                                                                                                                                        Forms\Get $get
                                                                                                                                    ) => $get('discount_amount_type') === DiscountAmountTypeEnum::PERCENTAGE ? '%' : '金额'),


                                                                                                      ]
                                                                                                  )->label(__('red-jasmine-coupon::coupon.labels.discount')),


                                                                                                  Forms\Components\TextInput::make('max_discount_amount')
                                                                                                                            ->label(__('red-jasmine-coupon::coupon.fields.max_discount_amount'))
                                                                                                                            ->numeric()
                                                                                                                            ->minValue(0)
                                                                                                                            ->visible(fn(
                                                                                                                                Forms\Get $get
                                                                                                                            ) => $get('discount_amount_type') === 'percentage'),


                                                                                              ])
                                                                                              ->columns(2),
                                                                  ]),

                                         // 有效期设置标签页
                                         Forms\Components\Tabs\Tab::make('validity_settings')
                                                                  ->label(__('red-jasmine-coupon::coupon.labels.validity_settings'))
                                                                  ->schema([
                                                                      Forms\Components\Section::make()
                                                                                              ->schema([
                                                                                                  Forms\Components\Select::make('validity_type')
                                                                                                                         ->label(__('red-jasmine-coupon::coupon.fields.validity_type'))
                                                                                                                         ->useEnum(ValidityTypeEnum::class)
                                                                                                                         ->default(ValidityTypeEnum::ABSOLUTE)
                                                                                                                         ->required()
                                                                                                                         ->live(),

                                                                                                  // 绝对时间设置
                                                                                                  Forms\Components\DateTimePicker::make('validity_start_time')
                                                                                                                                 ->label(__('red-jasmine-coupon::coupon.fields.validity_start_time'))
                                                                                                                                 ->visible(fn(
                                                                                                                                     Forms\Get $get
                                                                                                                                 ) => $get('validity_type') === ValidityTypeEnum::ABSOLUTE),

                                                                                                  Forms\Components\DateTimePicker::make('validity_end_time')
                                                                                                                                 ->label(__('red-jasmine-coupon::coupon.fields.validity_end_time'))
                                                                                                                                 ->visible(fn(
                                                                                                                                     Forms\Get $get
                                                                                                                                 ) => $get('validity_type') === ValidityTypeEnum::ABSOLUTE),
                                                                                                  // Forms\Components\Group::make(
                                                                                                  // 相对时间设置

                                                                                                  Cluster::make(
                                                                                                      [
                                                                                                          Forms\Components\TextInput::make('delayed_effective_time.value')
                                                                                                                                    ->label('时长')
                                                                                                                                    ->numeric()
                                                                                                                                    ->required()
                                                                                                                                    ->default(0)
                                                                                                                                    ->minValue(0),
                                                                                                          Forms\Components\Select::make('delayed_effective_time.unit')
                                                                                                                                 ->label('单位')
                                                                                                                                 ->useEnum(TimeUnitEnum::class)
                                                                                                                                 ->default(TimeUnitEnum::DAY)
                                                                                                                                 ->required(),
                                                                                                      ])
                                                                                                         ->label(__('red-jasmine-coupon::coupon.fields.delayed_effective_time'))
                                                                                                         ->name('delayed_effective_time')
                                                                                                         ->visible(fn(Forms\Get $get
                                                                                                         ) => $get('validity_type') === ValidityTypeEnum::RELATIVE),

                                                                                                  Cluster::make(
                                                                                                      [
                                                                                                          Forms\Components\TextInput::make('validity_time.value')
                                                                                                                                    ->label('时长')
                                                                                                                                    ->numeric()
                                                                                                                                    ->required()
                                                                                                                                    ->default(1)
                                                                                                                                    ->minValue(0),
                                                                                                          Forms\Components\Select::make('validity_time.unit')
                                                                                                                                 ->label('单位')
                                                                                                                                 ->useEnum(TimeUnitEnum::class)
                                                                                                                                 ->default(TimeUnitEnum::DAY)
                                                                                                                                 ->required(),
                                                                                                      ])
                                                                                                         ->label(__('red-jasmine-coupon::coupon.fields.validity_time'))
                                                                                                         ->name('validity_time')
                                                                                                         ->visible(fn(Forms\Get $get
                                                                                                         ) => $get('validity_type') === ValidityTypeEnum::RELATIVE),


                                                                                              ])
                                                                                              ->columns(3),
                                                                  ]),

                                         // 规则设置标签页
                                         Forms\Components\Tabs\Tab::make('rules_settings')
                                                                  ->label(__('red-jasmine-coupon::coupon.labels.rules_settings'))
                                                                  ->schema([
                                                                      Forms\Components\Section::make()
                                                                                              ->schema([
                                                                                                  Forms\Components\Repeater::make('usage_rules')
                                                                                                                           ->label(__('red-jasmine-coupon::coupon.fields.usage_rules'))
                                                                                                  ->default([])

                                                                                                  ,

                                                                                                  Forms\Components\Repeater::make('receive_rules')
                                                                                                                           ->label(__('red-jasmine-coupon::coupon.fields.receive_rules'))
                                                                                                      ->default([])

                                                                                                  ,
                                                                                              ]),
                                                                  ]),
                                     ])
                                     ->columnSpanFull(),

                ...static::operateFormSchemas(),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label(__('red-jasmine-coupon::coupon.fields.id'))
                                         ->copyable(),

                ...static::ownerTableColumns(),

                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine-coupon::coupon.fields.name'))
                                         ->searchable()
                ,

                Tables\Columns\ImageColumn::make('image')
                                          ->label(__('red-jasmine-coupon::coupon.fields.image'))
                                          ->circular()
                                          ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('discount_level')
                                         ->label(__('red-jasmine-coupon::coupon.fields.discount_level'))
                                         ->badge()
                                         ->useEnum(),

                Tables\Columns\TextColumn::make('discount_amount_type')
                                         ->label(__('red-jasmine-coupon::coupon.fields.discount_amount_type'))
                                         ->badge()
                                         ->useEnum(),

                Tables\Columns\TextColumn::make('label')
                                         ->label(__('red-jasmine-coupon::coupon.fields.label')),


                Tables\Columns\TextColumn::make('total_issued')
                                         ->label(__('red-jasmine-coupon::coupon.fields.total_issued'))
                ,

                Tables\Columns\TextColumn::make('total_used')
                                         ->label(__('red-jasmine-coupon::coupon.fields.total_used'))
                ,

                Tables\Columns\IconColumn::make('is_show')
                                         ->label(__('red-jasmine-coupon::coupon.fields.is_show'))
                                         ->boolean()
                ,

                Tables\Columns\TextColumn::make('status')
                                         ->label(__('red-jasmine-coupon::coupon.fields.status'))
                                         ->badge()
                                         ->useEnum()
                ,

                Tables\Columns\TextColumn::make('validity_start_time')
                                         ->label(__('red-jasmine-coupon::coupon.fields.validity_start_time'))
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('validity_end_time')
                                         ->label(__('red-jasmine-coupon::coupon.fields.validity_end_time'))
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),

                ...static::operateTableColumns(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                                           ->label(__('red-jasmine-coupon::coupon.filters.status'))
                                           ->options(CouponStatusEnum::options())
                                           ->multiple(),

                Tables\Filters\SelectFilter::make('validity_type')
                                           ->label(__('red-jasmine-coupon::coupon.filters.validity_type'))
                                           ->options(ValidityTypeEnum::options())
                                           ->multiple(),

                Tables\Filters\SelectFilter::make('discount_amount_type')
                                           ->label(__('red-jasmine-coupon::coupon.filters.discount_amount_type'))
                                           ->options(DiscountAmountTypeEnum::options())
                                           ->multiple(),

                Tables\Filters\Filter::make('is_show')
                                     ->label(__('red-jasmine-coupon::coupon.fields.is_show'))
                                     ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('publish')
                                     ->label(__('red-jasmine-coupon::coupon.commands.publish'))
                                     ->icon('heroicon-o-play')
                                     ->color('success')
                                     ->requiresConfirmation()
                                     ->action(function (Coupon $record) {
                                         $record->publish();
                                         $record->save();
                                     })
                                     ->visible(fn(Coupon $record) => $record->status === CouponStatusEnum::DRAFT),

                Tables\Actions\Action::make('pause')
                                     ->label(__('red-jasmine-coupon::coupon.commands.pause'))
                                     ->icon('heroicon-o-pause')
                                     ->color('warning')
                                     ->requiresConfirmation()
                                     ->action(function (Coupon $record) {
                                         $record->pause();
                                         $record->save();
                                     })
                                     ->visible(fn(Coupon $record) => $record->status === CouponStatusEnum::PUBLISHED),

                Tables\Actions\Action::make('expire')
                                     ->label(__('red-jasmine-coupon::coupon.commands.expire'))
                                     ->icon('heroicon-o-x-circle')
                                     ->color('danger')
                                     ->requiresConfirmation()
                                     ->action(function (Coupon $record) {
                                         $record->expire();
                                         $record->save();
                                     })
                                     ->visible(fn(Coupon $record) => in_array($record->status,
                                         [CouponStatusEnum::PUBLISHED, CouponStatusEnum::PAUSED])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations() : array
    {
        return [
            //
        ];
    }

    public static function getPages() : array
    {
        return [
            'index'  => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'view'   => Pages\ViewCoupon::route('/{record}'),
            'edit'   => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
} 