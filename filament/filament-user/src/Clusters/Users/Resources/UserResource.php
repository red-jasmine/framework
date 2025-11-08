<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Actions\Tables\UserSetTagsAction;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Actions\Tables\UserSetGroupAction;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Actions\Tables\UserSetStatusAction;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Actions\Tables\UserSetAccountAction;
use Filament\Actions\BulkActionGroup;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Pages\ListUsers;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Pages\CreateUser;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Pages\EditUser;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Pages;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\RelationManagers;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\UserCore\Application\Services\Commands\SetBaseInfo\UserSetBaseInfoCommand;
use RedJasmine\UserCore\Domain\Data\UserData;
use RedJasmine\UserCore\Domain\Enums\AccountTypeEnum;
use RedJasmine\UserCore\Domain\Enums\UserGenderEnum;
use RedJasmine\UserCore\Domain\Enums\UserStatusEnum;

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

    public static string $updateCommand = UserSetBaseInfoCommand::class;

    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 1;

    /**
     * @return string|null
     */
    public static function getModelLabel() : string
    {
        return __('red-jasmine-user::user.labels.title');
    }

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static ?string $cluster = Users::class;

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([

                Flex::make([
                    Section::make([
                        TextInput::make('nickname')
                                                  ->label(__('red-jasmine-user::user.fields.nickname'))
                                                  ->maxLength(64),
                        ToggleButtons::make('gender')
                                                      ->label(__('red-jasmine-user::user.fields.gender'))
                                                      ->inline()
                                                      ->useEnum(UserGenderEnum::class),
                        FileUpload::make('avatar')
                                                   ->label(__('red-jasmine-user::user.fields.avatar'))
                                                   ->image(),
                        DatePicker::make('birthday')
                                                   ->date()
                                                   ->label(__('red-jasmine-user::user.fields.birthday')),
                        TextInput::make('biography')
                                                  ->label(__('red-jasmine-user::user.fields.biography'))
                                                  ->maxLength(255),

                    ]),
                    Section::make([

                        TextInput::make('name')
                                                  ->label(__('red-jasmine-user::user.fields.name'))
                                                  ->required()
                                                  ->maxLength(64)

                        ,
                        TextInput::make('phone')
                                                  ->label(__('red-jasmine-user::user.fields.phone'))
                                                  ->maxLength(64)

                        ,
                        TextInput::make('email')
                                                  ->label(__('red-jasmine-user::user.fields.email'))
                                                  ->email()
                                                  ->maxLength(255)
                        ,
                        TextInput::make('password')
                                                  ->label(__('red-jasmine-user::user.fields.password'))
                                                  ->password()
                                                  ->maxLength(255)
                        ,

                        ToggleButtons::make('account_type')
                                                      ->label(__('red-jasmine-user::user.fields.account_type'))
                                                      ->inline()
                                                      ->default(AccountTypeEnum::PERSONAL)
                                                      ->useEnum(AccountTypeEnum::class),
                        ToggleButtons::make('status')
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
                TextColumn::make('id')
                                         ->label(__('red-jasmine-user::user.fields.id'))
                                         ->copyable()
                                         ->sortable(),
                TextColumn::make('name')
                                         ->label(__('red-jasmine-user::user.fields.name'))
                                         ->copyable()
                                         ->searchable()
                ,

                TextColumn::make('phone')
                                         ->formatStateUsing(fn($state) => Str::mask($state, '*', 3, 4))
                                         ->label(__('red-jasmine-user::user.fields.phone'))

                ,
                TextColumn::make('email')
                                         ->formatStateUsing(fn($state) => Str::mask($state, '*', 3, 4))
                                         ->label(__('red-jasmine-user::user.fields.email'))
                ,
                TextColumn::make('nickname')
                                         ->label(__('red-jasmine-user::user.fields.nickname'))
                                         ->searchable(),
                TextColumn::make('gender')
                                         ->label(__('red-jasmine-user::user.fields.gender'))
                                         ->useEnum(),
                ImageColumn::make('avatar')
                                          ->label(__('red-jasmine-user::user.fields.avatar'))
                ,
                TextColumn::make('birthday')
                                         ->label(__('red-jasmine-user::user.fields.birthday'))
                                         ->date('Y-m-d')
                ,

                TextColumn::make('account_type')
                                         ->label(__('red-jasmine-user::user.fields.account_type'))
                                         ->useEnum()
                ,

                TextColumn::make('group.name')
                                         ->badge()
                                         ->label(__('red-jasmine-user::user.relations.group'))
                ,

                TextColumn::make('status')
                                         ->label(__('red-jasmine-user::user.fields.status'))
                                         ->useEnum()
                ,
                TextColumn::make('last_active_at')
                                         ->label(__('red-jasmine-user::user.fields.last_active_at'))
                                         ->dateTime(),
                TextColumn::make('ip')
                                         ->label(__('red-jasmine-user::user.fields.ip'))
                                         ->dateTime(),

                TextColumn::make('country')
                                         ->label(__('red-jasmine-user::user.fields.country')),
                TextColumn::make('province')
                                         ->label(__('red-jasmine-user::user.fields.province')),
                TextColumn::make('city')
                                         ->label(__('red-jasmine-user::user.fields.city')),
                TextColumn::make('district')
                                         ->label(__('red-jasmine-user::user.fields.district')),

                TextColumn::make('school')
                                         ->label(__('red-jasmine-user::user.fields.school')),

                
            ])
            ->filters([
                SelectFilter::make('account_type')
                                           ->multiple()
                                           ->label(__('red-jasmine-user::user.fields.type'))
                                           ->options(AccountTypeEnum::options()),
                SelectFilter::make('status')
                                           ->multiple()
                                           ->label(__('red-jasmine-user::user.fields.status'))
                                           ->options(UserStatusEnum::options()),

            ], layout: FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
            ->recordUrl(null)
            ->recordActions([
                EditAction::make(),
                ActionGroup::make([
                    UserSetTagsAction::make()->setService(static::$service),
                    UserSetGroupAction::make()->setService(static::$service),
                    UserSetStatusAction::make()->setService(static::$service),
                    UserSetAccountAction::make()->setService(static::$service),
                ]),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
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
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }
}
