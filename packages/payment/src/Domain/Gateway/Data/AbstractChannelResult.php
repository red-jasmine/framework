<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

use RedJasmine\Support\Data\Data;

/**
 * 渠道返回的结果
 */
abstract class AbstractChannelResult extends Data
{

    public bool $successFul;

    public function isSuccessFul() : bool
    {
        return $this->successFul;
    }

    public function setSuccessFul(bool $successFul) : static
    {
        $this->successFul = $successFul;
        return $this;
    }


}
