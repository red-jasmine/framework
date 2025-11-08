<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use RedJasmine\Article\Application\Services\ArticleTag\ArticleTagApplicationService;
use RedJasmine\Article\Domain\Data\ArticleTagData;
use RedJasmine\Article\Domain\Models\ArticleTag;
use RedJasmine\FilamentArticle\Clusters\Articles;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages\CreateArticleTag;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages\EditArticleTag;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\Pages\ListArticleTags;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleTagResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentCore\Resources\Schemas\CategoryForm;

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

    use CategoryResource;

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
