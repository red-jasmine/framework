<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource\Pages;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagResource\RelationManagers;
use RedJasmine\User\Application\Services\UserTagApplicationService;
use RedJasmine\User\Domain\Data\UserTagData;
use RedJasmine\User\Domain\Enums\UserTagStatusEnum;
use RedJasmine\User\Domain\Models\UserTag;

class UserTagResource extends Resource
{


    use ResourcePageHelper;

    public static string $service   = UserTagApplicationService::class;
    public static string $dataClass = UserTagData::class;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-user::user-tag.labels.title');
    }

    protected static ?string $model = UserTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster        = Users::class;
    protected static ?int    $navigationSort = 4;

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                                       ->label(__('red-jasmine-user::user-tag.relations.category'))
                                       ->relationship('category', 'name'),
                Forms\Components\TextInput::make('name')
                                          ->label(__('red-jasmine-user::user-tag.fields.name'))
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('description')
                                          ->label(__('red-jasmine-user::user-tag.fields.description'))
                                          ->maxLength(255),
                Forms\Components\TextInput::make('icon')
                                          ->label(__('red-jasmine-user::user-tag.fields.icon'))
                                          ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                                          ->label(__('red-jasmine-user::user-tag.fields.color'))
                                         ,
                Forms\Components\TextInput::make('cluster')
                                          ->label(__('red-jasmine-user::user-tag.fields.cluster'))
                                          ->maxLength(255),
                Forms\Components\TextInput::make('sort')
                                          ->label(__('red-jasmine-user::user-tag.fields.sort'))
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\ToggleButtons::make('status')
                                          ->label(__('red-jasmine-user::user-tag.fields.status'))
                                          ->required()
                    ->inline()

                                          ->default(UserTagStatusEnum::ENABLE)
                                          ->useEnum(UserTagStatusEnum::class),
                Forms\Components\TextInput::make('extra')
                                          ->label(__('red-jasmine-user::user-tag.fields.extra')),
                ...static::operateFormSchemas(),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                                         ->label(__('red-jasmine-user::user-tag.relations.category'))
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine-user::user-tag.fields.name'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('description')
                                         ->label(__('red-jasmine-user::user-tag.fields.description'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                                         ->label(__('red-jasmine-user::user-tag.fields.icon'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('color')
                                         ->label(__('red-jasmine-user::user-tag.fields.color'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('cluster')
                                         ->label(__('red-jasmine-user::user-tag.fields.cluster'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('sort')
                                         ->label(__('red-jasmine-user::user-tag.fields.sort'))
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('status')
                                         ->label(__('red-jasmine-user::user-tag.fields.status'))
                                         ->useEnum()
                ,

                ...static::operateTableColumns(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index'  => Pages\ListUserTags::route('/'),
            'create' => Pages\CreateUserTag::route('/create'),
            'edit'   => Pages\EditUserTag::route('/{record}/edit'),
        ];
    }
}
