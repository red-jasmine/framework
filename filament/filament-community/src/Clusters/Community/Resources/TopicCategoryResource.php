<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use RedJasmine\Community\Application\Services\Category\TopicCategoryApplicationService;
use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\FilamentCommunity\Clusters\Community;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource\Pages\CreateTopicCategory;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource\Pages\EditTopicCategory;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource\Pages\ListTopicCategories;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicCategoryResource\RelationManagers;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentCore\Resources\CategoryResource;
use RedJasmine\Support\Presets\Category\Domain\Data\BaseCategoryData;

class TopicCategoryResource extends Resource
{
    use ResourcePageHelper;

    public static string     $service = TopicCategoryApplicationService::class;
    public static string $createCommand = BaseCategoryData::class;
    public static string $updateCommand = BaseCategoryData::class;
    protected static ?string $model   = TopicCategory::class;
    protected static ?string                $cluster        = Community::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    public static function getModelLabel() : string
    {
        return __('red-jasmine-support::category.labels.category');
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
            'index'  => ListTopicCategories::route('/'),
            'create' => CreateTopicCategory::route('/create'),
            'edit'   => EditTopicCategory::route('/{record}/edit'),
        ];
    }
}
