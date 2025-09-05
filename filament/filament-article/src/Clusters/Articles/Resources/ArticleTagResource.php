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

    protected static bool $onlyOwner     = true;
    public static string  $service       = ArticleTagApplicationService::class;
    public static string  $createCommand = ArticleTagData::class;
    public static string  $updateCommand = ArticleTagData::class;

    protected static ?string $model = ArticleTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Articles::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-article::article-tag.labels.article-tag');
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
            'index'  => Pages\ListArticleTags::route('/'),
            'create' => Pages\CreateArticleTag::route('/create'),
            'edit'   => Pages\EditArticleTag::route('/{record}/edit'),
        ];
    }
}
