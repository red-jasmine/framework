<?php

namespace RedJasmine\Order\Domain\Models\Features;

/**
 * @property int $star
 */
trait HasStar
{
    public function star(?int $star = null) : void
    {
        $this->star = $star;

        $this->fireModelEvent('starChanged', false);
    }

}
