<?php

namespace RedJasmine\Support\Contracts;

interface Owner
{
    /**
     * 用户类型
     * @return string|int
     */
    public function getType() : string|int;

    /**
     * 用户ID
     * @return int|string
     */
    public function getUid() : string|int;

}
