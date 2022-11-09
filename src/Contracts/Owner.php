<?php

namespace RedJasmine\Support\Contracts;

interface Owner
{
    /**
     * 用户类型
     * @return string|int
     */
    public function type() : string|int;

    /**
     * 用户ID
     * @return int|string
     */
    public function uid() : string|int;

}
