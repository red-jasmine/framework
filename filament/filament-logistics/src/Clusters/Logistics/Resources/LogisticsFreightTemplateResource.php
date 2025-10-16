<?php

namespace RedJasmine\FilamentLogistics\Clusters\Logistics\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsFreightTemplateResource\Pages\ListLogisticsFreightTemplates;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsFreightTemplateResource\Pages\CreateLogisticsFreightTemplate;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsFreightTemplateResource\Pages\EditLogisticsFreightTemplate;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Forms\Fields\MoneyInput;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentLogistics\Clusters\Logistics;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsFreightTemplateResource\Pages;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsFreightTemplateResource\RelationManagers;
use RedJasmine\Logistics\Application\Services\LogisticsFreightTemplateApplicationService;
use RedJasmine\Logistics\Domain\Data\LogisticsFreightTemplateData;
use RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates\FreightChargeTypeEnum;
use RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates\FreightTemplateStatusEnum;
use RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates\FreightTemplateStrategyTypeEnum;
use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;

class LogisticsFreightTemplateResource extends Resource
{

    use ResourcePageHelper;


    public static string $service   = LogisticsFreightTemplateApplicationService::class;
    public static string $dataClass = LogisticsFreightTemplateData::class;

    public static string $translationNamespace = 'red-jasmine-logistics::freight-template';


    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-table-cells';


    public static function getModelLabel() : string
    {
        return __('red-jasmine-logistics::freight-template.labels.title');
    }

    protected static ?string $model = LogisticsFreightTemplate::class;


    protected static ?string $cluster = Logistics::class;

    public static function form(Schema $schema) : Schema
    {
        $schema
            ->components([
                ...static::ownerFormSchemas(),
                TextInput::make('name')
                                          ->required()
                                          ->maxLength(255),
                Toggle::make('is_free')
                                       ->required(),
                ToggleButtons::make('charge_type')
                                              ->required()
                                              ->inline()->inlineLabel()
                                              ->useEnum(FreightChargeTypeEnum::class)
                                              ->default(FreightChargeTypeEnum::QUANTITY),


                Repeater::make('strategies')
                                         ->relationship('strategies')
                                         ->schema([
                                             ToggleButtons::make('type')->required()
                                                                           ->inline()
                                                                           ->inlineLabel()
                                                                           ->useEnum(FreightTemplateStrategyTypeEnum::class)
                                                                           ->default(FreightTemplateStrategyTypeEnum::CHARGE),

                                             Toggle::make('is_all_regions')
                                                                    ->default(true)
                                                                    ->live()
                                                                    ->required(),
                                             SelectTree::make('regions')
                                                       ->relationship('regions', 'name', 'parent_code', modifyChildQueryUsing: function (
                                                           $query
                                                       ) {
                                                           return $query->level(2);
                                                       })
                                                       ->default([])
                                                       ->inlineLabel()
                                                       ->enableBranchNode()
                                                       ->withCount()
                                                       ->parentNullValue('0')
                                                       ->grouped(true)
                                                        ->independent(false)
                                                       ->saveRelationshipsUsing(null)
                                                       ->dehydrated()
                                                       ->visible(fn(Get $get) => !$get('is_all_regions'))
                                             ,

                                             TextInput::make('standard_quantity')
                                                                       ->required()
                                                                       ->default(1)
                                                                       ->suffix('件')
                                                                       ->inlineLabel()
                                                                       ->numeric()
                                             ,
                                             MoneyInput::make('standard_fee')
                                                       ->inlineLabel()
                                                       ->default(['currency' => 'CNY', 'amount' => 0])
                                                       ->label(__(static::$translationNamespace.'.fields.strategies.standard_fee')),

                                             TextInput::make('extra_quantity')
                                                                       ->required()
                                                                       ->default(0)
                                                                       ->suffix('件')
                                                                       ->inlineLabel()
                                                                       ->numeric()
                                             ,
                                             MoneyInput::make('extra_fee')
                                                       ->inlineLabel()
                                                       ->default(['currency' => 'CNY', 'amount' => 0])
                                                       ->label(__(static::$translationNamespace.'.fields.strategies.extra_fee')),

                                         ])
                                         ->columns(2)
                                         ->dehydrated()
                                         ->saveRelationshipsUsing(null)
                ,


                TextInput::make('sort')
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                ToggleButtons::make('status')
                                              ->required()
                                              ->inline()->inlineLabel()
                                              ->useEnum(FreightTemplateStatusEnum::class)
                                              ->default(FreightTemplateStatusEnum::ENABLE),
                ...static::operateFormSchemas(),
            ])
            ->columns(1);


        return static::translationLabels($schema);
    }

    public static function table(Table $table) : Table
    {
        $table
            ->columns([
                TextColumn::make('id')
                                         ->label('ID')
                                         ->sortable(),
                ...static::ownerTableColumns(),
                TextColumn::make('name')
                                         ->searchable(),
                TextColumn::make('charge_type')
                                         ->useEnum()
                ,
                IconColumn::make('is_free')
                                         ->boolean(),
                TextColumn::make('sort')
                                         ->numeric()
                                         ->sortable(),
                TextColumn::make('status')
                                         ->useEnum(),
                ...static::operateTableColumns(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
        return static::translationLabels($table);
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
            'index'  => ListLogisticsFreightTemplates::route('/'),
            'create' => CreateLogisticsFreightTemplate::route('/create'),
            'edit'   => EditLogisticsFreightTemplate::route('/{record}/edit'),
        ];
    }
}
