<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use RedJasmine\Article\Application\Services\ArticleCategory\ArticleCategoryApplicationService;
use RedJasmine\Article\Domain\Data\ArticleCategoryData;
use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\FilamentArticle\Clusters\Articles;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\Pages\CreateArticleCategory;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\Pages\EditArticleCategory;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\Pages\ListArticleCategories;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\FilamentCore\Resources\Schemas\CategoryForm;
use RedJasmine\FilamentCore\Resources\Tables\CategoryTable;


class ArticleCategoryResource extends Resource
{

    use ResourcePageHelper;

    public static string  $service   = ArticleCategoryApplicationService::class;
    public static string $createCommand = ArticleCategoryData::class;
    public static string $updateCommand = ArticleCategoryData::class;
    protected static bool $onlyOwner = true;
    protected static ?string $model = ArticleCategory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $cluster = Articles::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-article::article-category.labels.title');
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
            'index'  => ListArticleCategories::route('/'),
            'create' => CreateArticleCategory::route('/create'),
            'edit'   => EditArticleCategory::route('/{record}/edit'),
        ];
    }
}
