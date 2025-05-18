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
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagCategoryResource\Pages;
use RedJasmine\FilamentUser\Clusters\Users\Resources\UserTagCategoryResource\RelationManagers;
use RedJasmine\Support\Domain\Models\Enums\CategoryStatusEnum;
use RedJasmine\User\Application\Services\UserTagCategoryApplicationService;
use RedJasmine\User\Domain\Data\UserTagCategoryData;
use RedJasmine\User\Domain\Models\UserTagCategory;

class UserTagCategoryResource extends Resource
{
    use ResourcePageHelper;

    public static string $service = UserTagCategoryApplicationService::class;

    public static string $dataClass = UserTagCategoryData::class;


    public static function getModelLabel() : string
    {
        return __('red-jasmine-user::user-tag-category.labels.title');
    }

    protected static ?string $model = UserTagCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';


    protected static ?string $cluster        = Users::class;
    protected static ?int    $navigationSort = 3;

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                SelectTree::make('parent_id')
                          ->label(__('red-jasmine-user::user-tag-category.relations.parent'))
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
                                          ->label(__('red-jasmine-user::user-tag-category.fields.name'))
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('description')
                                          ->label(__('red-jasmine-user::user-tag-category.fields.description'))
                                          ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                                           ->label(__('red-jasmine-user::user-tag-category.fields.image'))
                                           ->image(),
                Forms\Components\TextInput::make('cluster')
                                          ->label(__('red-jasmine-user::user-tag-category.fields.cluster'))
                                          ->maxLength(255),
                Forms\Components\TextInput::make('sort')
                                          ->label(__('red-jasmine-user::user-tag-category.fields.sort'))
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\Toggle::make('is_leaf')
                                       ->label(__('red-jasmine-user::user-tag-category.fields.is_leaf'))
                                       ->required(),
                Forms\Components\Toggle::make('is_show')
                                       ->label(__('red-jasmine-user::user-tag-category.fields.is_show'))
                                       ->required(),
                Forms\Components\ToggleButtons::make('status')
                                              ->label(__('red-jasmine-user::user-tag-category.fields.status'))
                                              ->required()
                                              ->inline()
                                              ->default(CategoryStatusEnum::ENABLE)
                                              ->useEnum(CategoryStatusEnum::class),
                Forms\Components\KeyValue::make('extra')
                                          ->label(__('red-jasmine-user::user-tag-category.fields.extra')),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label(__('red-jasmine-user::user-tag-category.fields.id'))
                                         ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                                         ->label(__('red-jasmine-user::user-tag-category.relations.parent'))
                                         ->sortable(),
                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine-user::user-tag-category.fields.name'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('description')
                                         ->label(__('red-jasmine-user::user-tag-category.fields.description'))
                                         ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                                          ->label(__('red-jasmine-user::user-tag-category.fields.image'))
                ,
                Tables\Columns\TextColumn::make('cluster')
                                         ->label(__('red-jasmine-user::user-tag-category.fields.cluster'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('sort')
                                         ->label(__('red-jasmine-user::user-tag-category.fields.sort'))
                                         ->sortable(),
                Tables\Columns\IconColumn::make('is_leaf')
                                         ->label(__('red-jasmine-user::user-tag-category.fields.is_leaf'))
                                         ->boolean(),
                Tables\Columns\IconColumn::make('is_show')
                                         ->label(__('red-jasmine-user::user-tag-category.fields.is_show'))
                                         ->boolean(),
                Tables\Columns\TextColumn::make('status')
                                         ->label(__('red-jasmine-user::user-tag-category.fields.status'))
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
            'index'  => Pages\ListUserTagCategories::route('/'),
            'create' => Pages\CreateUserTagCategory::route('/create'),
            'edit'   => Pages\EditUserTagCategory::route('/{record}/edit'),
        ];
    }
}
