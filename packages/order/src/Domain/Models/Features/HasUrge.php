<?php

namespace RedJasmine\Order\Domain\Models\Features;

trait HasUrge
{


    public function urge() : void
    {
        ++$this->urge;
        $this->urge_time = now();
        $this->fireModelEvent('urge', false);
    }
}
