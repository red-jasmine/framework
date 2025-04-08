<?php

namespace RedJasmine\FilamentArticle\Clusters\Articles\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use RedJasmine\Article\Application\Services\ArticleCategory\ArticleCategoryApplicationService;
use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\FilamentArticle\Clusters\Articles;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\Pages;
use RedJasmine\FilamentArticle\Clusters\Articles\Resources\ArticleCategoryResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\Support\Domain\Data\BaseCategoryData;


class ArticleCategoryResource extends Resource
{

    use ResourcePageHelper;

    public static string $service = ArticleCategoryApplicationService::class;


    public static string $createCommand = BaseCategoryData::class;
    public static string $updateCommand = BaseCategoryData::class;


    protected static ?string $model = ArticleCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $cluster = Articles::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-support::category.labels.category');
    }

    public static function form(Form $form) : Form
    {
        return static::categoryForm($form);
    }

    public static function table(Table $table) : Table
    {
        return static::categoryTable($table);
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
