<?php

namespace RedJasmine\Support\Data;


use Illuminate\Contracts\Support\Arrayable;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\DataPipeline;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;


#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
class Data extends \Spatie\LaravelData\Data
{

    public static function pipeline() : DataPipeline
    {
        $pipeline = parent::pipeline();
        $pipeline->firstThrough(UserInterfacePipeline::class);
        return $pipeline;
    }


}
