<?php

namespace RedJasmine\Order\Domain\Models\ValueObjects;

class Remarks
{
    protected string|null $remarks = null;

    public function __construct(string $remarks)
    {
        $this->remarks = $remarks;
        // TODO 验证
    }

    public function __toString() : string
    {
        return $this->remarks;
    }


}
