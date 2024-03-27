<?php

namespace RedJasmine\Support\Foundation\Service;

use Exception;
use RedJasmine\Support\Helpers\ID\Snowflake;

trait HasBuildId
{

    /**
     * @return int
     * @throws Exception
     */
    public static function buildID() : int
    {
        return Snowflake::getInstance()->nextId();
    }


}
