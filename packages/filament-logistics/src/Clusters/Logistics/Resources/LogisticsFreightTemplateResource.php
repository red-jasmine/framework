<?php

namespace RedJasmine\FilamentLogistics\Clusters\Logistics\Resources;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
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
use RedJasmine\Logistics\Domain\Models\Extensions\LogisticsFreightTemplateStrategy;
use RedJasmine\Logistics\Domain\Models\LogisticsFreightTemplate;
use RedJasmine\Region\Domain\Enums\RegionLevelEnum;

class LogisticsFreightTemplateResource extends Resource
{

    use ResourcePageHelper;


    public static string $service   = LogisticsFreightTemplateApplicationService::class;
    public static string $dataClass = LogisticsFreightTemplateData::class;

    public static string $translationNamespace = 'red-jasmine-logistics::freight-template';


    protected static ?string $navigationIcon = 'heroicon-o-table-cells';


    public static function getModelLabel() : string
    {
        return __('red-jasmine-logistics::freight-template.labels.title');
    }

    protected static ?string $model = LogisticsFreightTemplate::class;


    protected static ?string $cluster = Logistics::class;

    public static function form(Form $form) : Form
    {
        $form
            ->schema([
                ...static::ownerFormSchemas(),
                Forms\Components\TextInput::make('name')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\Toggle::make('is_free')
                                       ->required(),
                Forms\Components\ToggleButtons::make('charge_type')
                                              ->required()
                                              ->inline()->inlineLabel()
                                              ->useEnum(FreightChargeTypeEnum::class)
                                              ->default(FreightChargeTypeEnum::QUANTITY),


                Forms\Components\Repeater::make('strategies')
                                         ->relationship('strategies')
                                         ->schema([
                                             Forms\Components\ToggleButtons::make('type')->required()
                                                                           ->inline()
                                                                           ->inlineLabel()
                                                                           ->useEnum(FreightTemplateStrategyTypeEnum::class)
                                                                           ->default(FreightTemplateStrategyTypeEnum::CHARGE),

                                             Forms\Components\Toggle::make('is_all_regions')
                                                                    ->default(true)
                                                                    ->live()
                                                                    ->required(),
                                             SelectTree::make('regions')
                                                       ->relationship('regions', 'name', 'parent_code', modifyChildQueryUsing: function (
                                                           $query
                                                       ) {
                                                           return $query->levels([RegionLevelEnum::PROVINCE, RegionLevelEnum::CITY,]);
                                                       })
                                                       ->default([])
                                                       ->inlineLabel()
                                                       ->enableBranchNode()
                                                       ->withCount()
                                                       ->parentNullValue('0')
                                                       ->grouped(true)
                                                       ->saveRelationshipsUsing(null)
                                                       ->dehydrated()
                                                       ->visible(fn(Forms\Get $get) => !$get('is_all_regions'))
                                             ,

                                             Forms\Components\TextInput::make('standard_quantity')
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

                                             Forms\Components\TextInput::make('extra_quantity')
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
                                         ->columns(6)
                                         ->dehydrated()
                                         ->saveRelationshipsUsing(null)
                ,


                Forms\Components\TextInput::make('sort')
                                          ->required()
                                          ->numeric()
                                          ->default(0),
                Forms\Components\ToggleButtons::make('status')
                                              ->required()
                                              ->inline()->inlineLabel()
                                              ->useEnum(FreightTemplateStatusEnum::class)
                                              ->default(FreightTemplateStatusEnum::ENABLE),
                ...static::operateFormSchemas(),
            ])
            ->columns(1);


        return static::translationLabels($form);
    }

    public static function table(Table $table) : Table
    {
        $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label('ID')
                                         ->sortable(),
                ...static::ownerTableColumns(),
                Tables\Columns\TextColumn::make('name')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('charge_type')
                                         ->searchable(),
                Tables\Columns\IconColumn::make('is_free')
                                         ->boolean(),
                Tables\Columns\TextColumn::make('sort')
                                         ->numeric()
                                         ->sortable(),
                Tables\Columns\TextColumn::make('status')
                                         ->useEnum(),
                ...static::operateTableColumns(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index'  => Pages\ListLogisticsFreightTemplates::route('/'),
            'create' => Pages\CreateLogisticsFreightTemplate::route('/create'),
            'edit'   => Pages\EditLogisticsFreightTemplate::route('/{record}/edit'),
        ];
    }
}
