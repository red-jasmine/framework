<?php

namespace RedJasmine\Support\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property $owner
 */
interface OwnerInterface
{

    public function owner() : Attribute;

}
