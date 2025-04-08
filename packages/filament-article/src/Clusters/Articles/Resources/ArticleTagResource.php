<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\Article\Application\Services\ArticleTag\ArticleTagApplicationService;
use RedJasmine\Article\Domain\Data\ArticleTagData;
use RedJasmine\Article\Domain\Models\ArticleTag;
use RedJasmine\Article\Domain\Models\Enums\TagStatusEnum;
use RedJasmine\FilamentArticle\Clusters\Articles;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class ArticleTagResource extends Resource
{

    use ResourcePageHelper;

    public static string $service       = ArticleTagApplicationService::class;
    public static string $createCommand = ArticleTagData::class;
    public static string $updateCommand = ArticleTagData::class;

    protected static ?string $model = ArticleTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Articles::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-article::article-tag.labels.article-tag');
    }

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                ...static::ownerFormSchemas(),

                Forms\Components\Split::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('name')
                                                  ->label(__('red-jasmine-article::article-tag.fields.name'))
                                                  ->required()
                                                  ->maxLength(255),
                        Forms\Components\TextInput::make('description')
                                                  ->label(__('red-jasmine-article::article-tag.fields.description'))
                                                  ->maxLength(255),
                        Forms\Components\TextInput::make('icon')
                                                  ->label(__('red-jasmine-article::article-tag.fields.icon'))
                                                  ->maxLength(255),
                        Forms\Components\ColorPicker::make('color')
                                                    ->label(__('red-jasmine-article::article-tag.fields.color'))
                        ,
                        Forms\Components\TextInput::make('cluster')
                                                  ->label(__('red-jasmine-article::article-tag.fields.cluster'))
                                                  ->maxLength(255),

                    ]),
                    Forms\Components\Section::make([

                        Forms\Components\TextInput::make('sort')
                                                  ->label(__('red-jasmine-article::article-tag.fields.sort'))
                                                  ->required()
                                                  ->numeric()
                                                  ->default(0),
                        Forms\Components\Toggle::make('is_show')
                                               ->label(__('red-jasmine-article::article-tag.fields.is_show'))
                                               ->default(true)
                                               ->required(),
                        Forms\Components\Toggle::make('is_public')
                                               ->label(__('red-jasmine-article::article-tag.fields.is_public'))
                                               ->required(),
                        Forms\Components\ToggleButtons::make('status')
                                                      ->label(__('red-jasmine-article::article-tag.fields.status'))
                                                      ->required()
                                                      ->default(TagStatusEnum::ENABLE)
                                                      ->useEnum(TagStatusEnum::class),
                    ])->grow(false),
            ])->columnSpanFull(),




                ...static::operateFormSchemas(),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label(__('red-jasmine-article::article-tag.fields.id'))
                                         ->copyable()->sortable(),
                ...static::ownerTableColumns(),
                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine-article::article-tag.fields.name'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('description')
                                         ->label(__('red-jasmine-article::article-tag.fields.description'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                                         ->label(__('red-jasmine-article::article-tag.fields.icon'))
                                         ->searchable(),
                Tables\Columns\ColorColumn::make('color')
                                          ->label(__('red-jasmine-article::article-tag.fields.color'))
                ,
                Tables\Columns\TextColumn::make('cluster')
                                         ->label(__('red-jasmine-article::article-tag.fields.cluster'))
                                         ->searchable(),
                Tables\Columns\TextColumn::make('sort')
                                         ->label(__('red-jasmine-article::article-tag.fields.sort'))
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\IconColumn::make('is_show')
                                         ->label(__('red-jasmine-article::article-tag.fields.is_show'))
                                         ->boolean(),
                Tables\Columns\IconColumn::make('is_public')
                                         ->label(__('red-jasmine-article::article-tag.fields.is_public'))
                                         ->boolean(),
                Tables\Columns\TextColumn::make('status')
                                         ->label(__('red-jasmine-article::article-tag.fields.status'))
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
            'index'  => Pages\ListArticleTags::route('/'),
            'create' => Pages\CreateArticleTag::route('/create'),
            'edit'   => Pages\EditArticleTag::route('/{record}/edit'),
        ];
    }
}
