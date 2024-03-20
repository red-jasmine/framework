<?php

namespace RedJasmine\Support\DataTransferObjects;

use Illuminate\Support\Collection;
use Spatie\LaravelData\DataPipes\DataPipe;
use Spatie\LaravelData\Support\DataClass;

class MorphsDataPipe implements DataPipe
{
    public function handle(mixed $payload, DataClass $class, Collection $properties) : Collection
    {

        try {
            $morphs = $class->name::morphs();
            foreach ($morphs as $morph) {
                $this->initMorph($morph, $payload, $class, $properties);
            }
        } catch (\Throwable $throwable) {

        }
        return $properties;
    }


    protected function initMorph(string $morph, mixed $payload, DataClass $class, Collection $properties) : void
    {

        $typeKey     = $morph . '_type';
        $idKey       = $morph . '_id';
        $nicknameKey = $morph . '_nickname';
        $avatarKey   = $morph . '_avatar';
        if (!isset($properties[$morph]) && isset($payload[$typeKey], $payload[$idKey])) {
            $properties[$morph] = [
                'id'       => (int)$payload[$idKey],
                'type'     => $payload[$typeKey],
                'nickname' => $payload[$nicknameKey] ?? null,
                'avatar'   => $payload[$avatarKey] ?? null,
            ];
        }
    }


}
