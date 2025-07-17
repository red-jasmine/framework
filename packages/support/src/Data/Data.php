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
    protected string $primaryKey       = 'id';
    protected mixed  $_primaryKeyValue = null;

    public function getPrimaryKey() : string
    {
        return $this->primaryKey;
    }

    public function setPrimaryKey(string $primaryKey) : void
    {
        $this->primaryKey = $primaryKey;
    }


    public function getKey()
    {
        return $this->_primaryKeyValue;
    }

    public function setKey($key) : void
    {
        $this->_primaryKeyValue = $key;

    }


    public static function pipeline() : DataPipeline
    {
        $pipeline = parent::pipeline();
        $pipeline->firstThrough(UserInterfacePipeline::class);
        return $pipeline;
    }


}
