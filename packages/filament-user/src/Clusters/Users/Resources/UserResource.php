<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\Pages;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserResource\RelationManagers;
use RedJasmine\User\Application\Services\Commands\UserUpdateBaseInfoCommand;
use RedJasmine\User\Application\Services\UserApplicationService;
use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Enums\UserGenderEnum;
use RedJasmine\User\Domain\Enums\UserStatusEnum;
use RedJasmine\User\Domain\Enums\UserTypeEnum;
use RedJasmine\User\Domain\Models\User;

class UserResource extends Resource
{

    use ResourcePageHelper;

    public static string $service = UserApplicationService::class;

    public static string $createCommand = UserData::class;

    public static string $updateCommand = UserUpdateBaseInfoCommand::class;

    protected static ?string $model = User::class;

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
                        Forms\Components\Select::make('gender')
                                               ->label(__('red-jasmine-user::user.fields.gender'))
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
                                                  ->disabled()
                        ,
                        Forms\Components\TextInput::make('phone_number')
                                                  ->label(__('red-jasmine-user::user.fields.phone_number'))
                                                  ->maxLength(64)
                                                  ->disabled()
                        ,
                        Forms\Components\TextInput::make('email')
                                                  ->label(__('red-jasmine-user::user.fields.email'))
                                                  ->email()
                                                  ->maxLength(255)
                                                  ->disabled(),

                        Forms\Components\ToggleButtons::make('type')
                                                      ->disabled()
                                                      ->label(__('red-jasmine-user::user.fields.type'))
                                                      ->inline()
                                                      ->useEnum(UserTypeEnum::class),
                        Forms\Components\ToggleButtons::make('status')
                                                      ->label(__('red-jasmine-user::user.fields.status'))
                                                      ->disabled()
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
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine-user::user.fields.name'))
                                         ->copyable()
                ,
                Tables\Columns\TextColumn::make('phone_number')
                                         ->label(__('red-jasmine-user::user.fields.phone_number'))
                ,
                Tables\Columns\TextColumn::make('email')
                                         ->label(__('red-jasmine-user::user.fields.email'))
                                         ->searchable(),
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

                Tables\Columns\TextColumn::make('type')
                                         ->label(__('red-jasmine-user::user.fields.type'))
                                         ->useEnum()
                ,
                Tables\Columns\TextColumn::make('status')
                                         ->label(__('red-jasmine-user::user.fields.status'))
                                         ->useEnum()
                ,
                Tables\Columns\TextColumn::make('last_active_at')
                                         ->label(__('red-jasmine-user::user.fields.last_active_at'))
                                         ->dateTime()
                ,
                Tables\Columns\TextColumn::make('created_at')
                                         ->label(__('red-jasmine-user::user.fields.created_at'))
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
