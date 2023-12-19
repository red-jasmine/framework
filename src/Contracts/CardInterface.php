<?php

namespace RedJasmine\Card\Contracts;

use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Trade\Contracts\ItemInterface;

/**
 *
 */
interface CardInterface extends BelongsToOwnerInterface
{

    /**
     * @return string
     */
    public function content() : string;

    /**
     * 库存
     * @return int
     */
    public function stock() : int;

    /**
     * 批次号
     * @return int
     */
    public function batchNo() : int;


}
