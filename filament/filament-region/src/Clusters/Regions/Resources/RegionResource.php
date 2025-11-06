<?php

namespace RedJasmine\FilamentRegion\Clusters\Regions\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\FilamentCore\Filters\InputFilter;
use RedJasmine\FilamentCore\Helpers\ResourcePageHelper;
use RedJasmine\FilamentRegion\Clusters\Regions;
use RedJasmine\FilamentRegion\Clusters\Regions\Resources\RegionResource\Pages\ListRegions;
use RedJasmine\FilamentRegion\Clusters\Regions\Resources\RegionResource\Pages\CreateRegion;
use RedJasmine\FilamentRegion\Clusters\Regions\Resources\RegionResource\Pages\EditRegion;
use RedJasmine\Region\Application\Services\Region\Queries\FindQuery;
use RedJasmine\Region\Domain\Models\Region;
use RedJasmine\Region\Domain\Data\RegionData;
use RedJasmine\Region\Application\Services\Region\RegionApplicationService;
use RedJasmine\Region\Domain\Enums\RegionTypeEnum;
use RedJasmine\FilamentRegion\Forms\Components\CountrySelect;
use Symfony\Component\Intl\Countries;

class RegionResource extends Resource
{
    use ResourcePageHelper;

    protected static string $service = RegionApplicationService::class;
    protected static ?string $createCommand = RegionData::class;
    protected static ?string $updateCommand = RegionData::class;
    public static ?string $findQuery = FindQuery::class;
    protected static bool $onlyOwner = false;

    protected static ?string $model = Region::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $cluster = Regions::class;

    public static function getModelLabel(): string
    {
        return __('red-jasmine-filament-region::region.labels.title');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                CountrySelect::make('country_code')
                             ->label(__('red-jasmine-filament-region::region.fields.country_code'))
                             ->required()
                             ->live() // 实时响应，选择后自动刷新父级选择器
                             ->afterStateUpdated(function ($state, $set) {
                                 // 切换国家时清空父级选择
                                 $set('parent_code', null);
                             })
                             ->defaultChina(),

                TextInput::make('name')
                         ->label(__('red-jasmine-filament-region::region.fields.name'))
                         ->required()
                         ->maxLength(255),
                TextInput::make('code')
                         ->label(__('red-jasmine-filament-region::region.fields.code'))
                         ->required()
                         ->unique(ignoreRecord: true)
                         ->maxLength(64),

                Select::make('type')
                      ->label(__('red-jasmine-filament-region::region.fields.type'))
                      ->required()
                      ->useEnum(RegionTypeEnum::class)
                      ->default(RegionTypeEnum::PROVINCE),

                Select::make('parent_code')
                      ->label(__('red-jasmine-filament-region::region.fields.parent_code'))
                      ->relationship(
                          name: 'parent',
                          titleAttribute: 'name',
                          modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query

                              ->when($get('country_code'),
                                  fn($query, $value) => $query->where('country_code', $value))
                              ->when($record?->code, fn($query, $value) => $query->where('code', '<>', $value))
                              ->orderBy('level', 'asc')


                      )
                      ->searchable()
                      ->preload()
                      ->allowHtml()
                      ->getOptionLabelFromRecordUsing(fn(Region $record) => str_repeat('　', $record->level) . $record->name ."({$record->code})")
                      ->default(null)
                      ->placeholder(__('red-jasmine-filament-region::region.fields.parent_code_placeholder'))
                      ->disabled(fn(Get $get) => !$get('country_code'))
                      ->helperText(__('red-jasmine-filament-region::region.fields.parent_code_helper')),

                TextInput::make('region')
                         ->label(__('red-jasmine-filament-region::region.fields.region'))
                         ->maxLength(255),

                TextInput::make('level')
                         ->label(__('red-jasmine-filament-region::region.fields.level'))
                         ->numeric()
                         ->default(0)
                         ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('red-jasmine-filament-region::region.fields.code'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label(__('red-jasmine-filament-region::region.fields.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('country_code')
                    ->label(__('red-jasmine-filament-region::region.fields.country_code'))
                    ->sortable(),

                TextColumn::make('type')
                    ->label(__('red-jasmine-filament-region::region.fields.type'))
                    ->useEnum()
                    ->sortable(),

                TextColumn::make('region')
                    ->label(__('red-jasmine-filament-region::region.fields.region'))
                    ->searchable(),

                TextColumn::make('level')
                    ->label(__('red-jasmine-filament-region::region.fields.level'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('red-jasmine-filament-region::region.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('red-jasmine-filament-region::region.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('name')
                    ->label(__('red-jasmine-filament-region::region.fields.name'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('red-jasmine-filament-region::region.fields.name'))
                            ->placeholder('模糊搜索'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['name'],
                            fn (Builder $query, $value) => $query->where('name', 'like', "%{$value}%")
                        );
                    }),

                InputFilter::make('code')
                    ->label(__('red-jasmine-filament-region::region.fields.code')),

                Filter::make('parent_code')
                    ->label(__('red-jasmine-filament-region::region.fields.parent_code'))
                    ->schema([
                        TextInput::make('parent_code')
                            ->label(__('red-jasmine-filament-region::region.fields.parent_code')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['parent_code'],
                            fn (Builder $query, $value) => $query->where('parent_code', $value)
                        );
                    }),

                Filter::make('country_code')
                    ->label(__('red-jasmine-filament-region::region.fields.country_code'))
                    ->schema([
                        Select::make('country_code')
                            ->label(__('red-jasmine-filament-region::region.fields.country_code'))
                            ->options(function () {
                            return    Countries::getNames(app()->getLocale());

                            })
                            ->searchable()
                            ->multiple(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['country_code'],
                            fn (Builder $query, $value) => $query->whereIn('country_code', $value)
                        );
                    }),

                SelectFilter::make('type')
                    ->label(__('red-jasmine-filament-region::region.fields.type'))
                    ->options(RegionTypeEnum::options())
                    ->multiple(),

                Filter::make('level')
                    ->label(__('red-jasmine-filament-region::region.fields.level'))
                    ->schema([
                        TextInput::make('level')
                            ->label(__('red-jasmine-filament-region::region.fields.level'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10)
                            ->placeholder('输入层级数字'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['level'] !== null && $data['level'] !== '',
                            fn (Builder $query) => $query->where('level', $data['level'])
                        );
                    }),

            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns([
                'sm'  => 2,
                'lg'  => 3,
                'xl'  => 4,
                '2xl' => 5,
            ])
            ->deferFilters()
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('code');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRegions::route('/'),
            'create' => CreateRegion::route('/create'),
            'edit'   => EditRegion::route('/{record}/edit'),
        ];
    }
}

