<?php

namespace RedJasmine\Support\Domain\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property $owner
 */
interface OwnerInterface
{
    // 改为 Model 的多态关系

    public function owner() : MorphTo;

}
