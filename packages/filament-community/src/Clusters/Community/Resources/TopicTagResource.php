<?php

namespace RedJasmine\FilamentCommunity\Clusters\Community\Resources;

use Filament\Forms;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Community::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-community::topic-tag.labels.title');
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
            'index'  => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit'   => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}
