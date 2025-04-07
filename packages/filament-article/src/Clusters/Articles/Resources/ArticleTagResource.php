<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources;

use RedJasmine\FilamentArticle\Clusters\Articles;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\RelationManagers;
use RedJasmine\Article\Domain\Models\ArticleTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArticleTagResource extends Resource
{
    protected static ?string $model = ArticleTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Articles::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('owner_type')
                    ->required()
                    ->maxLength(64),
                Forms\Components\TextInput::make('owner_id')
                    ->required()
                    ->maxLength(64),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('icon')
                    ->maxLength(255),
                Forms\Components\TextInput::make('color')
                    ->maxLength(255),
                Forms\Components\TextInput::make('cluster')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_show')
                    ->required(),
                Forms\Components\Toggle::make('is_public')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(32),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('owner_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cluster')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_show')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_public')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticleTags::route('/'),
            'create' => Pages\CreateArticleTag::route('/create'),
            'edit' => Pages\EditArticleTag::route('/{record}/edit'),
        ];
    }
}
