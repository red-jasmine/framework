<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources;

use Filament\Schemas\Schema;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages\ListArticleTags;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages\CreateArticleTag;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages\EditArticleTag;
use Filament\Forms;
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

    protected static bool $onlyOwner     = true;
    public static string  $service       = ArticleTagApplicationService::class;
    public static string  $createCommand = ArticleTagData::class;
    public static string  $updateCommand = ArticleTagData::class;

    protected static ?string $model = ArticleTag::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Articles::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-article::article-tag.labels.article-tag');
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
            'index'  => ListArticleTags::route('/'),
            'create' => CreateArticleTag::route('/create'),
            'edit'   => EditArticleTag::route('/{record}/edit'),
        ];
    }
}
