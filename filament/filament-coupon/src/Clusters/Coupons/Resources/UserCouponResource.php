<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource\Pages\ListUserCoupons;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource\Pages\CreateUserCoupon;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource\Pages\ViewUserCoupon;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource\Pages\EditUserCoupon;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Data\UserCouponData;
use RedJasmine\Coupon\Domain\Models\Enums\UserCouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\FilamentCoupon\Clusters\Coupons;
use RedJasmine\FilamentCoupon\Clusters\Coupons\Resources\UserCouponResource\Pages;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class UserCouponResource extends Resource
{
    use ResourcePageHelper;

    /**
     * @var class-string<UserCouponApplicationService>
     */
    protected static string  $service       = UserCouponApplicationService::class;
    protected static ?string $createCommand = UserCouponData::class;
    protected static ?string $updateCommand = UserCouponData::class;
    protected static bool    $onlyOwner     = true;

    protected static ?string $model          = UserCoupon::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $cluster        = Coupons::class;
    protected static ?int    $navigationSort = 2;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-coupon::user_coupon.labels.user_coupon');
    }

    public static function getPluralModelLabel() : string
    {
        return __('red-jasmine-coupon::user_coupon.labels.user_coupon');
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.id'))
                                         ->sortable()
                                         ->copyable(),

                ...static::ownerTableColumns(),
                TextColumn::make('coupon_no')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.coupon_no'))

                ,
                TextColumn::make('coupon.label')
                                         ->label(__('red-jasmine-coupon::coupon.fields.label'))

                ,

                TextColumn::make('user_id')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.user_id'))
                                         ->copyable(),

                TextColumn::make('status')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.status'))
                                         ->badge()
                                         ->useEnum()
                ,
                TextColumn::make('issue_time')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.issue_time'))
                                         ->dateTime()
                                         ->sortable(),
                TextColumn::make('validity_start_time')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.validity_start_time'))
                                         ->dateTime()
                                         ->sortable(),
                TextColumn::make('validity_end_time')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.validity_end_time'))
                                         ->dateTime()
                                         ->sortable(),

                TextColumn::make('used_time')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.used_time'))
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('order_id')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.order_id'))
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),


                ...static::operateTableColumns(),
            ])
            ->filters([
                SelectFilter::make('status')
                                           ->label(__('red-jasmine-coupon::user_coupon.filters.status'))
                                           ->options(UserCouponStatusEnum::options())
                                           ->multiple(),

                SelectFilter::make('coupon_id')
                                           ->label(__('red-jasmine-coupon::user_coupon.fields.coupon_id'))
                                           ->relationship('coupon', 'name')
                                           ->searchable()
                                           ->preload()
                                           ->multiple(),

                Filter::make('user_id')
                                     ->label(__('red-jasmine-coupon::user_coupon.filters.user_id'))
                                     ->schema([
                                         TextInput::make('user_id')
                                                                   ->label(__('red-jasmine-coupon::user_coupon.fields.user_id'))
                                                                   ->numeric(),
                                     ])
                                     ->query(function ($query, array $data) {
                                         return $query->when(
                                             $data['user_id'],
                                             fn($query, $userId) => $query->where('user_id', $userId)
                                         );
                                     }),

                Filter::make('expire_time')
                                     ->label(__('red-jasmine-coupon::user_coupon.filters.date_range'))
                                     ->schema([
                                         DatePicker::make('expire_from')
                                                                    ->label('过期时间从'),
                                         DatePicker::make('expire_until')
                                                                    ->label('过期时间至'),
                                     ])
                                     ->query(function ($query, array $data) {
                                         return $query
                                             ->when(
                                                 $data['expire_from'],
                                                 fn($query, $date) => $query->whereDate('expire_time', '>=', $date)
                                             )
                                             ->when(
                                                 $data['expire_until'],
                                                 fn($query, $date) => $query->whereDate('expire_time', '<=', $date)
                                             );
                                     }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),

                Action::make('use')
                                     ->label(__('red-jasmine-coupon::user_coupon.commands.use'))
                                     ->icon('heroicon-o-check-circle')
                                     ->color('success')
                                     ->requiresConfirmation()
                                     ->schema([
                                         TextInput::make('order_id')
                                                                   ->label(__('red-jasmine-coupon::user_coupon.fields.order_id'))
                                                                   ->numeric()
                                                                   ->required(),
                                     ])
                                     ->action(function (UserCoupon $record, array $data) {
                                         $record->use($data['order_id']);
                                     })
                                     ->visible(fn(UserCoupon $record) => $record->isAvailable()),

                Action::make('expire')
                                     ->label(__('red-jasmine-coupon::user_coupon.commands.expire'))
                                     ->icon('heroicon-o-x-circle')
                                     ->color('danger')
                                     ->requiresConfirmation()
                                     ->action(function (UserCoupon $record) {
                                         $record->expire();
                                     })
                                     ->visible(fn(UserCoupon $record) => $record->status === UserCouponStatusEnum::AVAILABLE),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Section::make()
                                        ->schema([
                                            ...static::ownerFormSchemas(),

                                            Select::make('coupon_id')
                                                                   ->label(__('red-jasmine-coupon::user_coupon.fields.coupon_id'))
                                                                   ->relationship('coupon', 'name')
                                                                   ->searchable()
                                                                   ->preload()
                                                                   ->required(),

                                            TextInput::make('user_id')
                                                                      ->label(__('red-jasmine-coupon::user_coupon.fields.user_id'))
                                                                      ->numeric()
                                                                      ->required(),

                                            Select::make('status')
                                                                   ->label(__('red-jasmine-coupon::user_coupon.fields.status'))
                                                                   ->options(UserCouponStatusEnum::options())
                                                                   ->default(UserCouponStatusEnum::AVAILABLE)
                                                                   ->required(),

                                            DateTimePicker::make('issue_time')
                                                                           ->label(__('red-jasmine-coupon::user_coupon.fields.issue_time'))
                                                                           ->default(now())
                                                                           ->required(),

                                            DateTimePicker::make('expire_time')
                                                                           ->label(__('red-jasmine-coupon::user_coupon.fields.expire_time'))
                                                                           ->required(),

                                            DateTimePicker::make('used_time')
                                                                           ->label(__('red-jasmine-coupon::user_coupon.fields.used_time'))
                                                                           ->visible(fn(Get $get
                                                                           ) => $get('status') === UserCouponStatusEnum::USED->value),

                                            TextInput::make('order_id')
                                                                      ->label(__('red-jasmine-coupon::user_coupon.fields.order_id'))
                                                                      ->numeric()
                                                                      ->visible(fn(Get $get
                                                                      ) => $get('status') === UserCouponStatusEnum::USED->value),
                                        ])
                                        ->columns(2),

                ...static::operateFormSchemas(),
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
            'index'  => ListUserCoupons::route('/'),
            'create' => CreateUserCoupon::route('/create'),
            'view'   => ViewUserCoupon::route('/{record}'),
            'edit'   => EditUserCoupon::route('/{record}/edit'),
        ];
    }
} 