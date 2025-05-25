<?php

namespace RedJasmine\Support\Domain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;

class UserInterfaceCast implements CastsAttributes
{


    protected bool $withNickname = false;

    public function __construct(...$args)
    {

        $this->withNickname = (boolean) ($args[0] ?? false);
    }

    protected function getKeyName(string $key, string $field) : string
    {
        return $key.'_'.$field;
    }

    public function get(Model $model, string $key, mixed $value, array $attributes) : ?UserInterface
    {

        $typeKey = $this->getKeyName($key, 'type');
        $idKey   = $this->getKeyName($key, 'id');

        if (filled($attributes[$typeKey] ?? null) && filled($attributes[$idKey] ?? null)) {
            return UserData::from([
                'type'     => $attributes[$typeKey],
                'id'       => $attributes[$idKey],
                'nickname' => $attributes[$this->getKeyName($key, 'nickname')] ?? null,
            ]);
        }

        return null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes) : array
    {
        $typeKey     = $this->getKeyName($key, 'type');
        $idKey       = $this->getKeyName($key, 'id');
        $nicknameKey = $this->getKeyName($key, 'nickname');

        if (blank($value)) {
            return [
                $typeKey => null,
                $idKey   => null,
            ];
        }
        if (is_array($value)) {
            $value = UserData::from($value);
        }
        if ($value instanceof UserInterface) {
            return array_merge([
                $typeKey => $value->getType(),
                $idKey   => $value->getID(),
            ], $this->withNickname ? [
                $nicknameKey => $value->getNickname()
            ] : []);
        }

        return [];

    }


}