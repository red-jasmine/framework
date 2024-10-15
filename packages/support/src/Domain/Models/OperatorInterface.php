<?php

namespace RedJasmine\Support\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property $creator
 * @property $updater
 */
interface OperatorInterface
{


    /**
     * 创建者
     * @return Attribute
     */
    public function creator() : Attribute;


    /**
     * 更新者
     * @return Attribute
     */
    public function updater() : Attribute;


}
