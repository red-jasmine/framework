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

    protected $withOwnerNickname = true;

    protected $withOwnerAvatar = true;

    use HasOperator;

    public function getExtras() : array
    {
        return [
            'content'   => $this->content,
            'root_id'   => $this->root_id,
            'parent_id' => $this->parent_id,
            'is_top'    => $this->is_top,
            'is_good'   => $this->is_good,
            'is_hot'    => $this->is_hot,
        ];
    }


    public function setExtras(array $extras = []) : void
    {

        $this->content   = $extras['content'] ?? null;
        $this->parent_id = $extras['parent_id'] ?? 0;
        $this->root_id   = $extras['root_id'] ?? 0;

    }


}
