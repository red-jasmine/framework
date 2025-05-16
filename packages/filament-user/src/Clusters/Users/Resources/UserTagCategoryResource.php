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
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('description')
                                          ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                                           ->image(),
                Forms\Components\TextInput::make('cluster')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('sort')
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\Toggle::make('is_leaf')
                                       ->required(),
                Forms\Components\Toggle::make('is_show')
                                       ->required(),
                Forms\Components\TextInput::make('status')
                                          ->required()
                                          ->maxLength(32),
                Forms\Components\TextInput::make('extra'),
                Forms\Components\TextInput::make('version')
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\TextInput::make('creator_type')
                                          ->maxLength(64),
                Forms\Components\TextInput::make('creator_id')
                                          ->maxLength(64),
                Forms\Components\TextInput::make('updater_type')
                                          ->maxLength(64),
                Forms\Components\TextInput::make('updater_id')
                                          ->maxLength(64),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label('ID')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('name')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('description')
                                         ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('cluster')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('sort')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\IconColumn::make('is_leaf')
                                         ->boolean(),
                Tables\Columns\IconColumn::make('is_show')
                                         ->boolean(),
                Tables\Columns\TextColumn::make('status')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('version')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('creator_type')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('creator_id')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('updater_type')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('updater_id')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
