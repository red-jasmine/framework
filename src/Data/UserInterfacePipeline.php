<?php

namespace RedJasmine\Support\Data;

use RedJasmine\Support\Contracts\UserInterface;
use Spatie\LaravelData\DataPipes\DataPipe;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataClass;

class UserInterfacePipeline implements DataPipe
{
    public function handle(mixed $payload, DataClass $class, array $properties, CreationContext $creationContext) : array
    {

        foreach ($class->properties as $property) {
            if ($property->type->type->acceptsType(UserInterface::class) || $property->type->type->findAcceptedTypeForBaseType(UserInterface::class)) {
                $idKey   = $property->name . '_id';
                $typeKey = $property->name . '_type';
                if (isset($payload[$idKey], $payload[$typeKey]) && !isset($payload[$property->name])) {
                    $properties[$property->name] = [
                        'id'       => $payload[$idKey],
                        'type'     => $payload[$typeKey],
                        'avatar'   => $payload[$property->name . '_avatar'] ?? null,
                        'nickname' => $payload[$property->name . '_nickname'] ?? null,
                    ];

                }

            }
        }

        return $properties;

    }


}
