<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Domain\Data\CouponData;
use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Models\Enums\CouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountAmountTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\RuleObjectTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\RuleTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ThresholdTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\ValidityTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\DiscountLevelEnum;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCoupon\Clusters\Coupons;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource\Pages\CreateCoupon;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource\Pages\EditCoupon;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource\Pages\ListCoupons;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\CouponResource\Pages\ViewCoupon;
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

    protected static ?string                $model          = Coupon::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';
    protected static ?string                $cluster        = Coupons::class;
    protected static ?int                    $navigationSort = 1;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-coupon::coupon.labels.coupon');
    }

    public static function getPluralModelLabel() : string
    {
        return __('red-jasmine-coupon::coupon.labels.coupon');
    }

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Tabs::make('coupon_tabs')
                    ->tabs([
                        // 基础信息标签页
                        Tab::make('basic_info')
                           ->label(__('red-jasmine-coupon::coupon.labels.basic_info'))
                           ->schema([
                               Section::make()
                                      ->schema([
                                          ...static::ownerFormSchemas(),
                                          ...static::ownerFormSchemas('cost_bearer'),

                                          TextInput::make('name')
                                                   ->label(__('red-jasmine-coupon::coupon.fields.name'))
                                                   ->required()
                                                   ->maxLength(255),

                                          Textarea::make('description')
                                                  ->label(__('red-jasmine-coupon::coupon.fields.description'))
                                                  ->rows(3),

                                          FileUpload::make('image')
                                                    ->label(__('red-jasmine-coupon::coupon.fields.image'))
                                                    ->image()
                                                    ->directory('coupons'),

                                          Toggle::make('is_show')
                                                ->label(__('red-jasmine-coupon::coupon.fields.is_show'))
                                                ->default(true),

                                          Select::make('status')
                                                ->label(__('red-jasmine-coupon::coupon.fields.status'))
                                                ->useEnum(CouponStatusEnum::class)
                                                ->default(CouponStatusEnum::DRAFT)
                                                ->required(),

                                          TextInput::make('total_quantity')
                                                   ->label(__('red-jasmine-coupon::coupon.fields.total_quantity'))
                                                   ->numeric()
                                                   ->default(100)
                                                   ->required(),

                                          TextInput::make('sort')
                                                   ->label(__('red-jasmine-coupon::coupon.fields.sort'))
                                                   ->numeric()
                                                   ->default(0),

                                          Textarea::make('remarks')
                                                  ->label(__('red-jasmine-coupon::coupon.fields.remarks'))
                                                  ->rows(3),

                                          DateTimePicker::make('start_time')
                                                        ->default(Carbon::now()->startOfDay())
                                                        ->label(__('red-jasmine-coupon::coupon.fields.start_time')),

                                          DateTimePicker::make('end_time')
                                                        ->default(Carbon::now()->addMonth()->endOfDay())
                                                        ->label(__('red-jasmine-coupon::coupon.fields.end_time')),
                                      ])
                                      ->columns(2),
                           ]),

                        // 优惠设置标签页
                        Tab::make('discount_settings')
                           ->label(__('red-jasmine-coupon::coupon.labels.discount_settings'))
                           ->schema([
                               Section::make()
                                      ->schema([
                                          Select::make('discount_level')
                                                ->label(__('red-jasmine-coupon::coupon.fields.discount_level'))
                                                ->useEnum(DiscountLevelEnum::class)
                                                ->default(DiscountLevelEnum::ORDER)
                                                ->required(),

                                          Select::make('discount_amount_type')
                                                ->label(__('red-jasmine-coupon::coupon.fields.discount_amount_type'))
                                                ->useEnum(DiscountAmountTypeEnum::class)
                                                ->default(DiscountAmountTypeEnum::PERCENTAGE)
                                                ->required()
                                                ->live(),


                                          FusedGroup::make(
                                              [
                                                  TextInput::make('threshold_value')
                                                           ->label(__('red-jasmine-coupon::coupon.fields.threshold_value'))
                                                           ->numeric()
                                                           ->required()
                                                           ->prefix('满')
                                                           ->minValue(0),

                                                  Select::make('threshold_type')
                                                        ->label(__('red-jasmine-coupon::coupon.fields.threshold_type'))
                                                        ->useEnum(ThresholdTypeEnum::class)
                                                        ->default(ThresholdTypeEnum::AMOUNT)
                                                        ->required(),

                                                  TextInput::make('discount_amount_value')
                                                           ->label(__('red-jasmine-coupon::coupon.fields.discount_amount_value'))
                                                           ->numeric()
                                                           ->required()
                                                           ->minValue(0)
                                                           ->prefix(fn(
                                                               Get $get
                                                           ) => $get('discount_amount_type') === DiscountAmountTypeEnum::PERCENTAGE ? '打' : '减')
                                                           ->suffix(fn(
                                                               Get $get
                                                           ) => $get('discount_amount_type') === DiscountAmountTypeEnum::PERCENTAGE ? '%' : '金额'),


                                              ]
                                          )->label(__('red-jasmine-coupon::coupon.labels.discount')),


                                          TextInput::make('max_discount_amount')
                                                   ->label(__('red-jasmine-coupon::coupon.fields.max_discount_amount'))
                                                   ->numeric()
                                                   ->minValue(0)
                                                   ->visible(fn(
                                                       Get $get
                                                   ) => $get('discount_amount_type') === 'percentage'),


                                      ])
                                      ->columns(2),
                           ]),

                        // 有效期设置标签页
                        Tab::make('validity_settings')
                           ->label(__('red-jasmine-coupon::coupon.labels.validity_settings'))
                           ->schema([
                               Section::make()
                                      ->schema([
                                          Select::make('validity_type')
                                                ->label(__('red-jasmine-coupon::coupon.fields.validity_type'))
                                                ->useEnum(ValidityTypeEnum::class)
                                                ->default(ValidityTypeEnum::ABSOLUTE)
                                                ->required()
                                                ->live(),

                                          // 绝对时间设置
                                          DateTimePicker::make('validity_start_time')
                                                        ->label(__('red-jasmine-coupon::coupon.fields.validity_start_time'))
                                                        ->visible(fn(
                                                            Get $get
                                                        ) => $get('validity_type') === ValidityTypeEnum::ABSOLUTE),

                                          DateTimePicker::make('validity_end_time')
                                                        ->label(__('red-jasmine-coupon::coupon.fields.validity_end_time'))
                                                        ->visible(fn(
                                                            Get $get
                                                        ) => $get('validity_type') === ValidityTypeEnum::ABSOLUTE),
                                          // Forms\Components\Group::make(
                                          // 相对时间设置

                                          FusedGroup::make(
                                              [
                                                  TextInput::make('delayed_effective_time.value')
                                                           ->label('时长')
                                                           ->numeric()
                                                           ->required()
                                                           ->default(0)
                                                           ->minValue(0),
                                                  Select::make('delayed_effective_time.unit')
                                                        ->label('单位')
                                                        ->useEnum(TimeUnitEnum::class)
                                                        ->default(TimeUnitEnum::DAY)
                                                        ->required(),
                                              ])
                                                    ->label(__('red-jasmine-coupon::coupon.fields.delayed_effective_time'))
                                              //->name('delayed_effective_time')
                                                    ->visible(fn(Get $get
                                              ) => $get('validity_type') === ValidityTypeEnum::RELATIVE),

                                          FusedGroup::make(
                                              [
                                                  TextInput::make('validity_time.value')
                                                           ->label('时长')
                                                           ->numeric()
                                                           ->required()
                                                           ->default(1)
                                                           ->minValue(0),
                                                  Select::make('validity_time.unit')
                                                        ->label('单位')
                                                        ->useEnum(TimeUnitEnum::class)
                                                        ->default(TimeUnitEnum::DAY)
                                                        ->required(),
                                              ])
                                                    ->label(__('red-jasmine-coupon::coupon.fields.validity_time'))
                                              //->name('validity_time')
                                                    ->visible(fn(Get $get
                                              ) => $get('validity_type') === ValidityTypeEnum::RELATIVE),


                                      ])
                                      ->columns(3),
                           ]),

                        // 规则设置标签页
                        Tab::make('rules_settings')
                           ->label(__('red-jasmine-coupon::coupon.labels.rules_settings'))
                           ->schema([
                               Section::make()
                                      ->schema([
                                          Repeater::make('usage_rules')
                                                  ->label(__('red-jasmine-coupon::coupon.fields.usage_rules'))
                                                  ->schema([
                                                      Select::make('rule_type')
                                                            ->label('规则类型')
                                                            ->useEnum(RuleTypeEnum::class)
                                                            ->default(RuleTypeEnum::INCLUDE)
                                                            ->required(),
                                                      Select::make('object_type')
                                                            ->label('规则类型')
                                                            ->useEnum(RuleObjectTypeEnum::class)
                                                            ->default(RuleObjectTypeEnum::PRODUCT)
                                                            ->required(),
                                                      TextInput::make('object_value')
                                                               ->label('对象值')
                                                               ->required(),
                                                  ])
                                                  ->default([])

                                          ,

                                          Repeater::make('receive_rules')
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
                TextColumn::make('id')
                          ->label(__('red-jasmine-coupon::coupon.fields.id'))
                          ->copyable(),

                ...static::ownerTableColumns(),

                TextColumn::make('name')
                          ->label(__('red-jasmine-coupon::coupon.fields.name'))
                          ->searchable()
                ,

                ImageColumn::make('image')
                           ->label(__('red-jasmine-coupon::coupon.fields.image'))
                           ->circular()
                           ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('discount_level')
                          ->label(__('red-jasmine-coupon::coupon.fields.discount_level'))
                          ->badge()
                          ->useEnum(),

                TextColumn::make('discount_amount_type')
                          ->label(__('red-jasmine-coupon::coupon.fields.discount_amount_type'))
                          ->badge()
                          ->useEnum(),

                TextColumn::make('label')
                          ->label(__('red-jasmine-coupon::coupon.fields.label')),


                TextColumn::make('total_issued')
                          ->label(__('red-jasmine-coupon::coupon.fields.total_issued'))
                ,

                TextColumn::make('total_used')
                          ->label(__('red-jasmine-coupon::coupon.fields.total_used'))
                ,

                IconColumn::make('is_show')
                          ->label(__('red-jasmine-coupon::coupon.fields.is_show'))
                          ->boolean()
                ,

                TextColumn::make('status')
                          ->label(__('red-jasmine-coupon::coupon.fields.status'))
                          ->badge()
                          ->useEnum()
                ,

                TextColumn::make('validity_start_time')
                          ->label(__('red-jasmine-coupon::coupon.fields.validity_start_time'))
                          ->dateTime()
                          ->sortable()
                          ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('validity_end_time')
                          ->label(__('red-jasmine-coupon::coupon.fields.validity_end_time'))
                          ->dateTime()
                          ->sortable()
                          ->toggleable(isToggledHiddenByDefault: true),

                ...static::operateTableColumns(),
            ])
            ->filters([
                SelectFilter::make('status')
                            ->label(__('red-jasmine-coupon::coupon.filters.status'))
                            ->options(CouponStatusEnum::options())
                            ->multiple(),

                SelectFilter::make('validity_type')
                            ->label(__('red-jasmine-coupon::coupon.filters.validity_type'))
                            ->options(ValidityTypeEnum::options())
                            ->multiple(),

                SelectFilter::make('discount_amount_type')
                            ->label(__('red-jasmine-coupon::coupon.filters.discount_amount_type'))
                            ->options(DiscountAmountTypeEnum::options())
                            ->multiple(),

                Filter::make('is_show')
                      ->label(__('red-jasmine-coupon::coupon.fields.is_show'))
                      ->toggle(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),

                Action::make('publish')
                      ->label(__('red-jasmine-coupon::coupon.commands.publish'))
                      ->icon('heroicon-o-play')
                      ->color('success')
                      ->requiresConfirmation()
                      ->action(function (Coupon $record) {
                          $record->publish();
                          $record->save();
                      })
                      ->visible(fn(Coupon $record) => $record->status === CouponStatusEnum::DRAFT),

                Action::make('pause')
                      ->label(__('red-jasmine-coupon::coupon.commands.pause'))
                      ->icon('heroicon-o-pause')
                      ->color('warning')
                      ->requiresConfirmation()
                      ->action(function (Coupon $record) {
                          $record->pause();
                          $record->save();
                      })
                      ->visible(fn(Coupon $record) => $record->status === CouponStatusEnum::PUBLISHED),

                Action::make('expire')
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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index'  => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'view'   => ViewCoupon::route('/{record}'),
            'edit'   => EditCoupon::route('/{record}/edit'),
        ];
    }
} 