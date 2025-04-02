<?php

namespace RedJasmine\Interaction\Domain\Models\Records;

use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

/**
 * @property $content
 */
class InteractionRecordComment extends InteractionRecord implements OperatorInterface
{

    public $withOwnerNickname = true;

    public $withOwnerAvatar = true;

    use HasOperator;
}
