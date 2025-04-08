<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources;

use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

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
            'index'  => Pages\ListTopicCategories::route('/'),
            'create' => Pages\CreateTopicCategory::route('/create'),
            'edit'   => Pages\EditTopicCategory::route('/{record}/edit'),
        ];
    }
}
