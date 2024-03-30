<?php

namespace RedJasmine\Support\Data;


use Illuminate\Contracts\Support\Arrayable;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;


#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
class Data extends \Spatie\LaravelData\Data
{

    public static function morphs() : array
    {
        return [];
    }

    public static function casts() : array
    {
        return [

        ];
    }

    public static function from(mixed ...$payloads) : static
    {
        return static::factory()->from(...$payloads);
    }

    public function toArray() : array
    {
        $data   = parent::toArray();
        $morphs = static::morphs();
        foreach ($morphs as $morph) {
            $data = static::dotMorphFromArray($data, $morph);
        }
        return $data;
    }

    protected static function morphsData(array $data) : array
    {
        $morphs = static::morphs();
        foreach ($morphs as $morph) {
            $data = static::initMorphFromArray($data, $morph);
        }
        return $data;
    }

    protected static function initMorphFromArray(array $data, string $morph) : array
    {
        $typeKey     = $morph . '_type';
        $idKey       = $morph . '_id';
        $nicknameKey = $morph . '_nickname';
        $avatarKey   = $morph . '_avatar';
        if (!isset($data[$morph]) && (isset($data[$typeKey]) || isset($data[$idKey]))) {
            $data[$morph] = [
                'id'       => (int)$data[$idKey],
                'type'     => $data[$typeKey],
                'nickname' => $data[$nicknameKey] ?? null,
                'avatar'   => $data[$avatarKey] ?? null,
            ];
        }
        return $data;
    }

    protected static function dotMorphFromArray(array $data, string $morph) : array
    {
        if (isset($data[$morph])) {
            foreach ($data[$morph] as $key => $value) {
                $castKey        = $morph . '_' . $key;
                $data[$castKey] = $value;
            }


        }
        return $data;
    }

    public static function validate(array|Arrayable $payload) : Arrayable|array
    {

        return parent::validate(static::prepareForValidate($payload));
    }

    public static function prepareForValidate(array $properties) : array
    {
        return static::prepareForPipeline($properties);

    }


}
