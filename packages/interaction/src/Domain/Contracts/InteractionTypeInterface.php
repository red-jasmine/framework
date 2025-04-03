<?php

namespace RedJasmine\Interaction\Domain\Contracts;

use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;

/**
 * 互动类型接口
 */
interface InteractionTypeInterface
{

    /**
     * @return string|class-string<InteractionRecord>
     */
    public function getModelClass() : string;

    public function validate(InteractionData $data);

    public function makeRecord(InteractionData $data) : InteractionRecord;


    public function allowedFields() : array;
}