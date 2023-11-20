<?php

namespace RedJasmine\Card\Contracts;

use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Trade\Contracts\ItemInterface;

/**
 *
 */
interface CardInterface extends BelongsToOwnerInterface
{

    public function card() : string;

    public function quantity() : int;

    /**
     * 批次号
     * @return int
     */
    public function batch() : int;


}
