<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Pages;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\RelationManagers;
use RedJasmine\User\Application\Services\Commands\UserUpdateBaseInfoCommand;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Enums\UserGenderEnum;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use RedJasmine\User\Domain\Enums\AccountTypeEnum;
use RedJasmine\User\Domain\Models\User;

class UserResource extends Resource implements HasShieldPermissions
{
    public static function getPermissionPrefixes() : array
    {
        return array_merge(
            config('filament-shield.permission_prefixes.resource', []),
            ['setStatus', 'setGroup', 'setAccount']
        );

    }

    use ResourcePageHelper;

    public static string $service = UserApplicationService::class;

    public static string $createCommand = UserData::class;

    public static string $updateCommand = UserUpdateBaseInfoCommand::class;

    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 1;

    /**
     * @return string|null
     */
    public static function getModelLabel() : string
    {
        return __('red-jasmine-user::user.labels.title');
    }

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $cluster = Users::class;

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([

                Forms\Components\Split::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('nickname')
                                                  ->label(__('red-jasmine-user::user.fields.nickname'))
                                                  ->maxLength(64),
                        Forms\Components\ToggleButtons::make('gender')
                                                      ->label(__('red-jasmine-user::user.fields.gender'))
                                                      ->inline()
                                                      ->useEnum(UserGenderEnum::class),
                        Forms\Components\FileUpload::make('avatar')
                                                   ->label(__('red-jasmine-user::user.fields.avatar'))
                                                   ->image(),
                        Forms\Components\DatePicker::make('birthday')
                                                   ->date()
                                                   ->label(__('red-jasmine-user::user.fields.birthday')),
                        Forms\Components\TextInput::make('biography')
                                                  ->label(__('red-jasmine-user::user.fields.biography'))
                                                  ->maxLength(255),

                    ]),
                    Forms\Components\Section::make([

                        Forms\Components\TextInput::make('name')
                                                  ->label(__('red-jasmine-user::user.fields.name'))
                                                  ->required()
                                                  ->maxLength(64)

                        ,
                        Forms\Components\TextInput::make('phone')
                                                  ->label(__('red-jasmine-user::user.fields.phone'))
                                                  ->maxLength(64)

                        ,
                        Forms\Components\TextInput::make('email')
                                                  ->label(__('red-jasmine-user::user.fields.email'))
                                                  ->email()
                                                  ->maxLength(255)
                        ,
                        Forms\Components\TextInput::make('password')
                                                  ->label(__('red-jasmine-user::user.fields.password'))
                                                  ->password()
                                                  ->maxLength(255)
                        ,

                        Forms\Components\ToggleButtons::make('account_type')
                                                      ->label(__('red-jasmine-user::user.fields.account_type'))
                                                      ->inline()
                                                      ->default(AccountTypeEnum::PERSONAL)
                                                      ->useEnum(AccountTypeEnum::class),
                        Forms\Components\ToggleButtons::make('status')
                                                      ->label(__('red-jasmine-user::user.fields.status'))
                                                      ->inline()
                                                      ->default(UserStatusEnum::ACTIVATED)
                                                      ->useEnum(UserStatusEnum::class),

                    ])->grow(false),


                ])->columnSpanFull(),


            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label(__('red-jasmine-user::user.fields.id'))
                                         ->copyable()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine-user::user.fields.name'))
                                         ->copyable()
                                         ->searchable()
                ,

                Tables\Columns\TextColumn::make('phone')
                                         ->formatStateUsing(fn($state) => Str::mask($state, '*', 3, 4))
                                         ->label(__('red-jasmine-user::user.fields.phone'))

                ,
                Tables\Columns\TextColumn::make('email')
                                         ->formatStateUsing(fn($state) => Str::mask($state, '*', 3, 4))
                                         ->label(__('red-jasmine-user::user.fields.email'))
                ,
                Tables\Columns\TextColumn::make('nickname')
                                         ->label(__('red-jasmine-user::user.fields.nickname'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                                         ->label(__('red-jasmine-user::user.fields.gender'))
                                         ->useEnum(),
                Tables\Columns\ImageColumn::make('avatar')
                                          ->label(__('red-jasmine-user::user.fields.avatar'))
                ,
                Tables\Columns\TextColumn::make('birthday')
                                         ->label(__('red-jasmine-user::user.fields.birthday'))
                                         ->date('Y-m-d')
                ,

                Tables\Columns\TextColumn::make('account_type')
                                         ->label(__('red-jasmine-user::user.fields.account_type'))
                                         ->useEnum()
                ,

                Tables\Columns\TextColumn::make('group.name')
                                         ->badge()
                                         ->label(__('red-jasmine-user::user.relations.group'))
                ,

                Tables\Columns\TextColumn::make('status')
                                         ->label(__('red-jasmine-user::user.fields.status'))
                                         ->useEnum()
                ,
                Tables\Columns\TextColumn::make('last_active_at')
                                         ->label(__('red-jasmine-user::user.fields.last_active_at'))
                                         ->dateTime(),
                Tables\Columns\TextColumn::make('ip')
                                         ->label(__('red-jasmine-user::user.fields.ip'))
                                         ->dateTime(),

                Tables\Columns\TextColumn::make('country')
                                         ->label(__('red-jasmine-user::user.fields.country')),
                Tables\Columns\TextColumn::make('province')
                                         ->label(__('red-jasmine-user::user.fields.province')),
                Tables\Columns\TextColumn::make('city')
                                         ->label(__('red-jasmine-user::user.fields.city')),
                Tables\Columns\TextColumn::make('district')
                                         ->label(__('red-jasmine-user::user.fields.district')),

                Tables\Columns\TextColumn::make('school')
                                         ->label(__('red-jasmine-user::user.fields.school')),

                ...static::operateTableColumns(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('account_type')
                                           ->multiple()
                                           ->label(__('red-jasmine-user::user.fields.type'))
                                           ->options(AccountTypeEnum::options()),
                Tables\Filters\SelectFilter::make('status')
                                           ->multiple()
                                           ->label(__('red-jasmine-user::user.fields.status'))
                                           ->options(UserStatusEnum::options()),

            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
            ->recordUrl(null)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    UserResource\Actions\Tables\UserSetTagsAction::make()->setService(static::$service),
                    UserResource\Actions\Tables\UserSetGroupAction::make()->setService(static::$service),
                    UserResource\Actions\Tables\UserSetStatusAction::make()->setService(static::$service),
                    UserResource\Actions\Tables\UserSetAccountAction::make()->setService(static::$service),
                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
