<?php

namespace RedJasmine\FilamentLogistics\Clusters\Logistics\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsCompanyResource\Pages\ListLogisticsCompanies;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsCompanyResource\Pages\CreateLogisticsCompany;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsCompanyResource\Pages\EditLogisticsCompany;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentLogistics\Clusters\Logistics;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsCompanyResource\Pages;
use RedJasmine\FilamentLogistics\Clusters\Logistics\Resources\LogisticsCompanyResource\RelationManagers;
use RedJasmine\Logistics\Application\Services\LogisticsCompanyApplicationService;
use RedJasmine\Logistics\Domain\Data\LogisticsCompanyData;
use RedJasmine\Logistics\Domain\Models\Enums\Companies\CompanyTypeEnum;
use RedJasmine\Logistics\Domain\Models\LogisticsCompany;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;

class LogisticsCompanyResource extends Resource
{
    use ResourcePageHelper;


    public static string $service   = LogisticsCompanyApplicationService::class;
    public static string $dataClass = LogisticsCompanyData::class;

    public static string $translationNamespace = 'red-jasmine-logistics::logistics-companies';

    protected static ?string $model = LogisticsCompany::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $cluster = Logistics::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-logistics::logistics-companies.labels.title');
    }


    public static function form(Schema $schema) : Schema
    {
        $schema
            ->components([
                TextInput::make('code')
                                          ->required()
                                          ->maxLength(255),
                TextInput::make('name')
                                          ->required()
                                          ->maxLength(255),

                ToggleButtons::make('type')
                                              ->required()
                                              ->inlineLabel()
                                              ->inline()
                                              ->useEnum(CompanyTypeEnum::class)
                                             ,
                ToggleButtons::make('status')
                                              ->required()
                                              ->inlineLabel()
                                              ->inline()
                                              ->useEnum(UniversalStatusEnum::class)
                                              ->default(UniversalStatusEnum::ENABLE),
                FileUpload::make('logo')
                                          ->image(255),
                TextInput::make('tel')
                                          ->tel()
                                          ->maxLength(255),
                TextInput::make('url')
                                          ->maxLength(255),


                ...static::operateFormSchemas(),
            ]);

        return static::translationLabels($schema);
    }

    public static function table(Table $table) : Table
    {
        $table
            ->columns([
                TextColumn::make('code')
                                         ->searchable(),
                TextColumn::make('name')
                                         ->searchable(),
                TextColumn::make('logo')
                                         ->searchable(),
                TextColumn::make('tel')
                                         ->searchable(),
                TextColumn::make('url')
                                         ->searchable(),
                TextColumn::make('type')
                                         ->useEnum(),
                TextColumn::make('status')
                                         ->useEnum(),
                ...static::operateTableColumns()
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
            'index'  => ListLogisticsCompanies::route('/'),
            'create' => CreateLogisticsCompany::route('/create'),
            'edit'   => EditLogisticsCompany::route('/{record}/edit'),
        ];
    }
}
