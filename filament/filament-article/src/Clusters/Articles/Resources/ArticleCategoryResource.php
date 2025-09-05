<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources;

use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $cluster = Articles::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-article::article-category.labels.title');
    }

    public static function form(Form $form) : Form
    {
        return static::categoryForm($form, static::$onlyOwner ?? false);
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
            'index'  => Pages\ListArticleCategories::route('/'),
            'create' => Pages\CreateArticleCategory::route('/create'),
            'edit'   => Pages\EditArticleCategory::route('/{record}/edit'),
        ];
    }
}
