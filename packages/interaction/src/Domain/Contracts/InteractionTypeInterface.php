<?php

namespace RedJasmine\Interaction\Domain\Contracts;

use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;

/**
 * 互动类型接口
 */
interface InteractionTypeInterface
{
    public function validate(InteractionData $data);


    public function makeRecord(InteractionData $data) : InteractionRecord;
}