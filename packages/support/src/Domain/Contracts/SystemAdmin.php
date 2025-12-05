<?php

namespace RedJasmine\Support\Domain\Contracts;

/**
 * 系统管理员
 */
interface SystemAdmin
{
    /**
     * 是否为系统管理员
     * @return bool
     */
    public function isSystemAdmin() : bool;
}