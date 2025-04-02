<?php

namespace RedJasmine\Interaction\Domain\Types;

use Illuminate\Support\Carbon;
use RedJasmine\Interaction\Domain\Contracts\InteractionTypeInterface;
use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;

abstract class BaseInteractionType implements InteractionTypeInterface
{

    
    public function validate(InteractionData $data)
    {
        $data->quantity = 1;
        // TODO: Implement validate() method.
    }

    public function makeRecord(InteractionData $data) : InteractionRecord
    {
        $interactionRecord                   = InteractionRecord::make();
        $interactionRecord->resource_type    = $data->resourceType;
        $interactionRecord->resource_id      = $data->resourceId;
        $interactionRecord->interaction_type = $data->interactionType;
        $interactionRecord->quantity         = $data->quantity;
        $interactionRecord->owner            = $data->user;
        $interactionRecord->interaction_time = Carbon::now();
        return $interactionRecord;
    }


}