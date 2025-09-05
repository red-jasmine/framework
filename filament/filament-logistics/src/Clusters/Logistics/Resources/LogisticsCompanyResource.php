<?php

namespace RedJasmine\FilamentLogistics\Clusters\Logistics\Resources;

use Filament\Forms;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $cluster = Logistics::class;

    public static function getModelLabel() : string
    {
        return __('red-jasmine-logistics::logistics-companies.labels.title');
    }


    public static function form(Form $form) : Form
    {
        $form
            ->schema([
                Forms\Components\TextInput::make('code')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('name')
                                          ->required()
                                          ->maxLength(255),

                Forms\Components\ToggleButtons::make('type')
                                              ->required()
                                              ->inlineLabel()
                                              ->inline()
                                              ->useEnum(CompanyTypeEnum::class)
                                             ,
                Forms\Components\ToggleButtons::make('status')
                                              ->required()
                                              ->inlineLabel()
                                              ->inline()
                                              ->useEnum(UniversalStatusEnum::class)
                                              ->default(UniversalStatusEnum::ENABLE),
                Forms\Components\FileUpload::make('logo')
                                          ->image(255),
                Forms\Components\TextInput::make('tel')
                                          ->tel()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('url')
                                          ->maxLength(255),


                ...static::operateFormSchemas(),
            ]);

        return static::translationLabels($form);
    }

    public static function table(Table $table) : Table
    {
        $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('name')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('logo')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('tel')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('url')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('type')
                                         ->useEnum(),
                Tables\Columns\TextColumn::make('status')
                                         ->useEnum(),
                ...static::operateTableColumns()
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
            'index'  => Pages\ListLogisticsCompanies::route('/'),
            'create' => Pages\CreateLogisticsCompany::route('/create'),
            'edit'   => Pages\EditLogisticsCompany::route('/{record}/edit'),
        ];
    }
}
