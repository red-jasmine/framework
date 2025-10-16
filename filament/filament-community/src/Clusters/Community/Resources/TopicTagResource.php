<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources;

use Filament\Schemas\Schema;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicTagResource\Pages\ListTags;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicTagResource\Pages\CreateTag;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicTagResource\Pages\EditTag;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\Community\Application\Services\Tag\TopicTagApplicationService;
use RedJasmine\Community\Domain\Data\TopicTagData;
use RedJasmine\Community\Domain\Models\Enums\TagStatusEnum;
use RedJasmine\Community\Domain\Models\TopicTag;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicTagResource\Pages;
use RedJasmine\FilamentCommunity\Clusters\Community\Resources\TopicTagResource\RelationManagers;
use RedJasmine\FilamentCommunity\Clusters\Community;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;

class TopicTagResource extends Resource
{

    use ResourcePageHelper;

    public static string $service       = TopicTagApplicationService::class;
    public static string $createCommand = TopicTagData::class;
    public static string $updateCommand = TopicTagData::class;

    protected static ?string $model = TopicTag::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Community::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-community::topic-tag.labels.title');
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
            'index'  => ListTags::route('/'),
            'create' => CreateTag::route('/create'),
            'edit'   => EditTag::route('/{record}/edit'),
        ];
    }
}
