<?php

namespace RedJasmine\FilamentCore\Resources\JsonSchemaResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use RedJasmine\FilamentCore\Resources\JsonSchemaResource;

class EditJsonSchema extends EditRecord
{
    protected static string $resource = JsonSchemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // 确保 schema 是数组
        if (empty($data['schema']) || !is_array($data['schema'])) {
            $data['schema'] = [
                '$schema' => 'http://json-schema.org/draft-07/schema#',
                'type' => 'object',
                'properties' => [],
            ];
        }

        return $data;
    }
}

