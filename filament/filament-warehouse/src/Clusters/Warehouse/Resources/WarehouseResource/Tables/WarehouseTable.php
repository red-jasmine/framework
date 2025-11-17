<?php

namespace RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use RedJasmine\FilamentWarehouse\Clusters\Warehouse\Resources\WarehouseResource;
use RedJasmine\Warehouse\Domain\Models\Enums\WarehouseTypeEnum;

class WarehouseTable
{
    /**
     * 配置表格
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(static::getColumns())
            ->filters(static::getFilters())
            ->recordActions(static::getRecordActions())
            ->toolbarActions(static::getToolbarActions());
    }

    /**
     * 获取表格列
     */
    protected static function getColumns(): array
    {
        return [
            TextColumn::make('id')
                ->label(__('red-jasmine-warehouse::warehouse.fields.id'))
                ->copyable()
                ->sortable(),

            TextColumn::make('code')
                ->label(__('red-jasmine-warehouse::warehouse.fields.code'))
                ->searchable()
                ->copyable()
                ->sortable(),

            TextColumn::make('name')
                ->label(__('red-jasmine-warehouse::warehouse.fields.name'))
                ->searchable()
                ->copyable()
                ->sortable(),

            TextColumn::make('warehouse_type')
                ->label(__('red-jasmine-warehouse::warehouse.fields.warehouse_type'))
                ->badge()
                ->useEnum(WarehouseTypeEnum::class)
                ->sortable(),

            TextColumn::make('address')
                ->label(__('red-jasmine-warehouse::warehouse.fields.address'))
                ->limit(30)
                ->tooltip(fn($record) => $record->address)
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('contact_phone')
                ->label(__('red-jasmine-warehouse::warehouse.fields.contact_phone'))
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('contact_person')
                ->label(__('red-jasmine-warehouse::warehouse.fields.contact_person'))
                ->toggleable(isToggledHiddenByDefault: true),

            IconColumn::make('is_active')
                ->label(__('red-jasmine-warehouse::warehouse.fields.is_active'))
                ->boolean()
                ->sortable(),

            IconColumn::make('is_default')
                ->label(__('red-jasmine-warehouse::warehouse.fields.is_default'))
                ->boolean()
                ->sortable(),

            ...WarehouseResource::ownerTableColumns(),
            ...WarehouseResource::operateTableColumns(),
        ];
    }

    /**
     * 获取筛选器
     */
    protected static function getFilters(): array
    {
        return [
            SelectFilter::make('warehouse_type')
                ->label(__('red-jasmine-warehouse::warehouse.fields.warehouse_type'))
                ->options(WarehouseTypeEnum::options()),

            TernaryFilter::make('is_active')
                ->label(__('red-jasmine-warehouse::warehouse.fields.is_active')),

            TernaryFilter::make('is_default')
                ->label(__('red-jasmine-warehouse::warehouse.fields.is_default')),
        ];
    }

    /**
     * 获取记录操作
     */
    protected static function getRecordActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    /**
     * 获取工具栏操作
     */
    protected static function getToolbarActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}

