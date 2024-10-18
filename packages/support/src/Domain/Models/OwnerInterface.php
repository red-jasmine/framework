<?php

namespace RedJasmine\Support\Domain\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property $owner
 */
interface OwnerInterface
{


    public function owner() : MorphTo;

}
