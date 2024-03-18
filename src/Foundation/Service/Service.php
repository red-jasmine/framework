<?php

namespace RedJasmine\Support\Foundation\Service;

use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\Helpers\ID\Snowflake;

abstract class Service
{

    use HasActions;

    use WithUserService;

    use WithClientService;

    protected static string $model = Model::class;

    protected static string $data = Data::class;

    /**
     * 操作管道配置前缀
     * @var string|null
     */
    public static ?string $actionPipelinesConfigPrefix = null;


    /**
     * @return int
     * @throws Exception
     */
    public static function buildID() : int
    {
        return Snowflake::getInstance()->nextId();
    }


    /**
     * @return string|null|Model
     */
    public static function getModel() : ?string
    {
        return static::$model;
    }

    public static function getDataClass()
    {
        return static::$data;
    }
}
