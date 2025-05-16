<?php

namespace RedJasmine\FilamentUser\Clusters\Users\Resources;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentUser\Clusters\Users;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource\Pages;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserGroupResource\RelationManagers;
use RedJasmine\Support\Domain\Models\Enums\CategoryStatusEnum;
use RedJasmine\User\Application\Services\UserGroupApplicationService;
use RedJasmine\User\Domain\Data\UserGroupData;
use RedJasmine\User\Domain\Models\UserGroup;

class UserGroupResource extends Resource
{


    use ResourcePageHelper;

    public static string $service = UserGroupApplicationService::class;

    public static string $dataClass = UserGroupData::class;


    protected static ?string $model = UserGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $cluster = Users::class;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-user::user-group.labels.title');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                SelectTree::make('parent_id')
                          ->label(__('red-jasmine-user::user-group.relations.parent'))
                          ->relationship(relationship: 'parent', titleAttribute: 'name', parentAttribute: 'parent_id',
                              modifyQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->when($record?->getKey(),
                                  fn($query, $value) => $query->where('id', '<>', $value)),
                              modifyChildQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->when($record?->getKey(),
                                  fn($query, $value) => $query->where('id', '<>', $value)),
                          )
                          ->searchable()
                          ->default(0)
                          ->enableBranchNode()
                          ->parentNullValue(0)
                ,
                Forms\Components\TextInput::make('name')
                                          ->label(__('red-jasmine-user::user-group.fields.name'))
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('description')
                                          ->label(__('red-jasmine-user::user-group.fields.description'))
                                          ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                                           ->label(__('red-jasmine-user::user-group.fields.image'))
                                           ->image(),
                Forms\Components\TextInput::make('cluster')
                                          ->label(__('red-jasmine-user::user-group.fields.cluster'))
                                          ->maxLength(255),
                Forms\Components\TextInput::make('sort')
                                          ->label(__('red-jasmine-user::user-group.fields.sort'))
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\Toggle::make('is_leaf')
                                       ->label(__('red-jasmine-user::user-group.fields.is_leaf'))
                                       ->required(),
                Forms\Components\Toggle::make('is_show')
                                       ->label(__('red-jasmine-user::user-group.fields.is_show'))
                                       ->required(),
                Forms\Components\ToggleButtons::make('status')
                                              ->label(__('red-jasmine-user::user-group.fields.status'))
                                              ->required()
                    ->inline()
                                              ->default(CategoryStatusEnum::ENABLE)
                                              ->useEnum(CategoryStatusEnum::class),
                Forms\Components\KeyValue::make('extra')
                                          ->label(__('red-jasmine-user::user-group.fields.extra')),
                ...static::operateFormSchemas(),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label(__('red-jasmine-user::user-group.fields.id'))
                                         ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                                         ->label(__('red-jasmine-user::user-group.relations.parent'))
                                         ->sortable(),
                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine-user::user-group.fields.name'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('description')
                                         ->label(__('red-jasmine-user::user-group.fields.description'))
                                         ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                                          ->label(__('red-jasmine-user::user-group.fields.image'))
                ,
                Tables\Columns\TextColumn::make('cluster')
                                         ->label(__('red-jasmine-user::user-group.fields.cluster'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('sort')
                                         ->label(__('red-jasmine-user::user-group.fields.sort'))
                                         ->sortable(),
                Tables\Columns\IconColumn::make('is_leaf')
                                         ->label(__('red-jasmine-user::user-group.fields.is_leaf'))
                                         ->boolean(),
                Tables\Columns\IconColumn::make('is_show')
                                         ->label(__('red-jasmine-user::user-group.fields.is_show'))
                                         ->boolean(),
                Tables\Columns\TextColumn::make('status')
                                         ->label(__('red-jasmine-user::user-group.fields.status'))
                                         ->useEnum(),
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
            'index'  => Pages\ListUserGroups::route('/'),
            'create' => Pages\CreateUserGroup::route('/create'),
            'edit'   => Pages\EditUserGroup::route('/{record}/edit'),
        ];
    }
}
