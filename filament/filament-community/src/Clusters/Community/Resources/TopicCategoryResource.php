<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources;

use Filament\Schemas\Schema;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource\Pages\ListTopicCategories;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource\Pages\CreateTopicCategory;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource\Pages\EditTopicCategory;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use RedJasmine\Community\Application\Services\Category\TopicCategoryApplicationService;
use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\FilamentCommunity\Clusters\Community;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource\Pages;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\Support\Domain\Data\BaseCategoryData;

class TopicCategoryResource extends Resource
{
    use ResourcePageHelper;

    public static string $service = TopicCategoryApplicationService::class;
    protected static ?string $model = TopicCategory::class;
    protected static ?string $cluster = Community::class;

    public static string $createCommand = BaseCategoryData::class;
    public static string $updateCommand = BaseCategoryData::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    public static function getModelLabel() : string
    {
        return __('red-jasmine-support::category.labels.category');
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
            'index'  => ListTopicCategories::route('/'),
            'create' => CreateTopicCategory::route('/create'),
            'edit'   => EditTopicCategory::route('/{record}/edit'),
        ];
    }
}
