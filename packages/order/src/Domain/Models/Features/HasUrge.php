<?php

namespace RedJasmine\Order\Domain\Models\Features;

use Illuminate\Support\Carbon;

/**
 * @property ?Carbon $urge_time
 */
trait HasUrge
{


    public function urge() : void
    {
        ++$this->urge;
        $this->urge_time = now();
        $this->fireModelEvent('urge', false);
    }
}
