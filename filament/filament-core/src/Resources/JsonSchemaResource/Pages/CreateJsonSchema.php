<?php

namespace RedJasmine\FilamentCore\Resources\JsonSchemaResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RedJasmine\FilamentCore\Resources\JsonSchemaResource;

class CreateJsonSchema extends CreateRecord
{
    protected static string $resource = JsonSchemaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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

