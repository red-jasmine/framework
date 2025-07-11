<?php

namespace RedJasmine\Support\Domain\Casts;

use http\Exception\InvalidArgumentException;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class UserInterfaceCastTransformer implements Cast, Transformer
{

    public function transform(DataProperty $property, mixed $value, TransformationContext $context) : mixed
    {
        if ($value instanceof UserInterface) {
            return [
                'id'       => $value->getId(),
                'type'     => $value->getType(),
                'avatar'   => $value->getAvatar(),
                'nickname' => $value->getNickname(),
            ];
        }
        return $value;
    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context) : mixed
    {

        // 判断类型

        if ($value instanceof UserInterface) {
            return $value;
        }


        if (is_array($value)) {

            return UserData::from($value);
        }

        throw new InvalidArgumentException($property->name . ' error');

    }


}
