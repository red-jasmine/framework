<?php

namespace RedJasmine\Support\DataTransferObjects;


use Illuminate\Contracts\Support\Arrayable;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\DataPipeline;
use Spatie\LaravelData\DataPipes\AuthorizedDataPipe;
use Spatie\LaravelData\DataPipes\CastPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\DefaultValuesDataPipe;
use Spatie\LaravelData\DataPipes\FillRouteParameterPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\MapPropertiesDataPipe;
use Spatie\LaravelData\DataPipes\ValidatePropertiesDataPipe;
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


    public static function validate(array|Arrayable $payload) : Arrayable|array
    {

        return parent::validate(static::prepareForValidate($payload));
    }

    public static function prepareForValidate(array $properties) : array
    {
        return static::prepareForPipeline($properties);

    }


}
