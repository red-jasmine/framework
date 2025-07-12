<?php

namespace RedJasmine\FilamentCoupon\Clusters\Coupons\Resources;

use Filament\Forms;
use Filament\Forms\Form;
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
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
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
                Tables\Columns\TextColumn::make('id')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.id'))
                                         ->sortable()
                                         ->copyable(),

                ...static::ownerTableColumns(),
                Tables\Columns\TextColumn::make('coupon_no')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.coupon_no'))

                ,
                Tables\Columns\TextColumn::make('coupon.label')
                                         ->label(__('red-jasmine-coupon::coupon.fields.label'))

                ,

                Tables\Columns\TextColumn::make('user_id')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.user_id'))
                                         ->copyable(),

                Tables\Columns\TextColumn::make('status')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.status'))
                                         ->badge()
                                         ->useEnum()
                ,
                Tables\Columns\TextColumn::make('issue_time')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.issue_time'))
                                         ->dateTime()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('validity_start_time')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.validity_start_time'))
                                         ->dateTime()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('validity_end_time')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.validity_end_time'))
                                         ->dateTime()
                                         ->sortable(),

                Tables\Columns\TextColumn::make('used_time')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.used_time'))
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('order_id')
                                         ->label(__('red-jasmine-coupon::user_coupon.fields.order_id'))
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),


                ...static::operateTableColumns(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                                           ->label(__('red-jasmine-coupon::user_coupon.filters.status'))
                                           ->options(UserCouponStatusEnum::options())
                                           ->multiple(),

                Tables\Filters\SelectFilter::make('coupon_id')
                                           ->label(__('red-jasmine-coupon::user_coupon.fields.coupon_id'))
                                           ->relationship('coupon', 'name')
                                           ->searchable()
                                           ->preload()
                                           ->multiple(),

                Tables\Filters\Filter::make('user_id')
                                     ->label(__('red-jasmine-coupon::user_coupon.filters.user_id'))
                                     ->form([
                                         Forms\Components\TextInput::make('user_id')
                                                                   ->label(__('red-jasmine-coupon::user_coupon.fields.user_id'))
                                                                   ->numeric(),
                                     ])
                                     ->query(function ($query, array $data) {
                                         return $query->when(
                                             $data['user_id'],
                                             fn($query, $userId) => $query->where('user_id', $userId)
                                         );
                                     }),

                Tables\Filters\Filter::make('expire_time')
                                     ->label(__('red-jasmine-coupon::user_coupon.filters.date_range'))
                                     ->form([
                                         Forms\Components\DatePicker::make('expire_from')
                                                                    ->label('过期时间从'),
                                         Forms\Components\DatePicker::make('expire_until')
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('use')
                                     ->label(__('red-jasmine-coupon::user_coupon.commands.use'))
                                     ->icon('heroicon-o-check-circle')
                                     ->color('success')
                                     ->requiresConfirmation()
                                     ->form([
                                         Forms\Components\TextInput::make('order_id')
                                                                   ->label(__('red-jasmine-coupon::user_coupon.fields.order_id'))
                                                                   ->numeric()
                                                                   ->required(),
                                     ])
                                     ->action(function (UserCoupon $record, array $data) {
                                         $record->use($data['order_id']);
                                     })
                                     ->visible(fn(UserCoupon $record) => $record->isAvailable()),

                Tables\Actions\Action::make('expire')
                                     ->label(__('red-jasmine-coupon::user_coupon.commands.expire'))
                                     ->icon('heroicon-o-x-circle')
                                     ->color('danger')
                                     ->requiresConfirmation()
                                     ->action(function (UserCoupon $record) {
                                         $record->expire();
                                     })
                                     ->visible(fn(UserCoupon $record) => $record->status === UserCouponStatusEnum::AVAILABLE),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                                        ->schema([
                                            ...static::ownerFormSchemas(),

                                            Forms\Components\Select::make('coupon_id')
                                                                   ->label(__('red-jasmine-coupon::user_coupon.fields.coupon_id'))
                                                                   ->relationship('coupon', 'name')
                                                                   ->searchable()
                                                                   ->preload()
                                                                   ->required(),

                                            Forms\Components\TextInput::make('user_id')
                                                                      ->label(__('red-jasmine-coupon::user_coupon.fields.user_id'))
                                                                      ->numeric()
                                                                      ->required(),

                                            Forms\Components\Select::make('status')
                                                                   ->label(__('red-jasmine-coupon::user_coupon.fields.status'))
                                                                   ->options(UserCouponStatusEnum::options())
                                                                   ->default(UserCouponStatusEnum::AVAILABLE)
                                                                   ->required(),

                                            Forms\Components\DateTimePicker::make('issue_time')
                                                                           ->label(__('red-jasmine-coupon::user_coupon.fields.issue_time'))
                                                                           ->default(now())
                                                                           ->required(),

                                            Forms\Components\DateTimePicker::make('expire_time')
                                                                           ->label(__('red-jasmine-coupon::user_coupon.fields.expire_time'))
                                                                           ->required(),

                                            Forms\Components\DateTimePicker::make('used_time')
                                                                           ->label(__('red-jasmine-coupon::user_coupon.fields.used_time'))
                                                                           ->visible(fn(Forms\Get $get
                                                                           ) => $get('status') === UserCouponStatusEnum::USED->value),

                                            Forms\Components\TextInput::make('order_id')
                                                                      ->label(__('red-jasmine-coupon::user_coupon.fields.order_id'))
                                                                      ->numeric()
                                                                      ->visible(fn(Forms\Get $get
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
            'index'  => Pages\ListUserCoupons::route('/'),
            'create' => Pages\CreateUserCoupon::route('/create'),
            'view'   => Pages\ViewUserCoupon::route('/{record}'),
            'edit'   => Pages\EditUserCoupon::route('/{record}/edit'),
        ];
    }
} 