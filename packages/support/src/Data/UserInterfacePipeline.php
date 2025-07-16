<?php

namespace RedJasmine\Support\Data;

use Illuminate\Support\Str;
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

                $name    = Str::snake($property->name);
                $idKey   = $name.'_id';
                $typeKey = $name.'_type';
                if (isset($payload[$idKey], $payload[$typeKey])
                    && !isset($payload[$name])

                    && filled($payload[$idKey]) && filled($payload[$typeKey])
                ) {
                    $properties[$property->name] = UserData::from([
                        'id'       => $payload[$idKey],
                        'type'     => $payload[$typeKey],
                        'avatar'   => $payload[$property->name.'_avatar'] ?? null,
                        'nickname' => $payload[$property->name.'_nickname'] ?? null,
                    ]);
                }

            }
        }

        return $properties;

    }


}
