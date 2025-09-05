<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductStockLogResource;

class ListProductStockLogs extends ListRecords
{
    protected static string $resource = ProductStockLogResource::class;




    protected function paginateTableQuery(Builder $query) : Paginator|CursorPaginator
    {
        return $query->simplePaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
