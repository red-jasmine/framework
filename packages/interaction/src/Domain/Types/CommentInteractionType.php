<?php

namespace RedJasmine\Interaction\Domain\Types;

use Illuminate\Support\Carbon;
use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Models\Records\InteractionRecordComment;

class CommentInteractionType extends BaseInteractionType
{
    public function makeRecord(InteractionData $data) : InteractionRecord
    {

        $interactionRecord                   = InteractionRecordComment::make();
        $interactionRecord->resource_type    = $data->resourceType;
        $interactionRecord->resource_id      = $data->resourceId;
        $interactionRecord->interaction_type = $data->interactionType;
        // $interactionRecord->quantity         = $data->quantity;
        $interactionRecord->owner            = $data->user;
        $interactionRecord->interaction_time = Carbon::now();
        $interactionRecord->content         = $data->extras['content'] ?? '';
        return $interactionRecord;
    }


}