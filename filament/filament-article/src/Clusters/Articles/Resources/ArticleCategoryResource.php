<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources;

use Filament\Schemas\Schema;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\Pages\ListArticleCategories;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\Pages\CreateArticleCategory;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\Pages\EditArticleCategory;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use RedJasmine\Article\Application\Services\ArticleCategory\ArticleCategoryApplicationService;
use RedJasmine\Article\Domain\Data\ArticleCategoryData;
use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\FilamentArticle\Clusters\Articles;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\Pages;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\Support\Domain\Data\BaseCategoryData;


class ArticleCategoryResource extends Resource
{

    use ResourcePageHelper;

    public static string  $service   = ArticleCategoryApplicationService::class;
    protected static bool $onlyOwner = true;

    public static string $createCommand = ArticleCategoryData::class;
    public static string $updateCommand = ArticleCategoryData::class;


    protected static ?string $model = ArticleCategory::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $cluster = Articles::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-article::article-category.labels.title');
    }

    public static function form(Schema $schema) : Schema
    {
        return static::categoryForm($schema, static::$onlyOwner ?? false);
    }

    public static function table(Table $table) : Table
    {
        return static::categoryTable($table, static::$onlyOwner ?? false);
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
            'index'  => ListArticleCategories::route('/'),
            'create' => CreateArticleCategory::route('/create'),
            'edit'   => EditArticleCategory::route('/{record}/edit'),
        ];
    }
}
