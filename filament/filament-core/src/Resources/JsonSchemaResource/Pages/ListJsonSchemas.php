<?php

namespace RedJasmine\FilamentCore\Resources\JsonSchemaResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RedJasmine\FilamentCore\Resources\JsonSchemaResource;

class ListJsonSchemas extends ListRecords
{
    protected static string $resource = JsonSchemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

