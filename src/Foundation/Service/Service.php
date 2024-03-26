<?php

namespace RedJasmine\Support\Foundation\Service;

use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\DataTransferObjects\Data;
use RedJasmine\Support\Helpers\ID\Snowflake;

abstract class Service
{

    /**
     * TODO
     * 引入可配置化
     */
    use HasActions;

    use WithUserService;

    use WithClientService;




    /**
     * @return int
     * @throws Exception
     */
    public static function buildID() : int
    {
        return Snowflake::getInstance()->nextId();
    }


}
