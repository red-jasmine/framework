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
     * 创建人
     * @return Attribute
     */
    public function creator() : Attribute;

    /**
     * 修改人
     * @return Attribute
     */
    public function updater() : Attribute;


}
