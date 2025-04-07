<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\Article\Application\Services\Article\ArticleApplicationService;
use RedJasmine\Article\Domain\Data\ArticleData;
use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\FilamentArticle\Clusters\Articles;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\Pages;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;

class ArticleResource extends Resource
{

    use ResourcePageHelper;

    /**
     * @var class-string<ProductApplicationService::class>
     */
    protected static ?string $service = ArticleApplicationService::class;

    protected static ?string $createCommand = ArticleData::class;
    protected static ?string $updateCommand = ArticleData::class;


    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-c-document-text';

    protected static ?string $cluster = Articles::class;

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('owner_type')
                                          ->required()
                                          ->maxLength(64),
                Forms\Components\TextInput::make('owner_id')
                                          ->required()
                                          ->maxLength(64),
                Forms\Components\TextInput::make('title')
                                          ->required()
                                          ->maxLength(255),

                Forms\Components\Textarea::make('content')

                                          ,
                Forms\Components\FileUpload::make('image')
                                           ->image(),
                Forms\Components\TextInput::make('description')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('keywords')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('status')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\Select::make('category_id')
                                       ->relationship('category', 'name'),
                Forms\Components\Toggle::make('is_top')
                                       ->required(),
                Forms\Components\TextInput::make('sort')
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\TextInput::make('approval_status')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('version')
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\TextInput::make('creator_type')
                                          ->maxLength(32),
                Forms\Components\TextInput::make('creator_id')
                                          ->maxLength(64),
                Forms\Components\TextInput::make('updater_type')
                                          ->maxLength(32),
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
                Tables\Columns\TextColumn::make('owner_type')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('owner_id')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('title')
                                         ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('description')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('keywords')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('status')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\IconColumn::make('is_top')
                                         ->boolean(),
                Tables\Columns\TextColumn::make('sort')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('approval_status')
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
            'index'  => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit'   => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
