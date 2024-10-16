<?php

namespace RedJasmine\Support\Domain\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property $creator
 * @property $updater
 */
interface OperatorInterface
{


    /**
     * 创建者
     * @return MorphTo
     */
    public function creator() : MorphTo;


    /**
     * 更新者
     * @return MorphTo
     */
    public function updater() : MorphTo;


}
