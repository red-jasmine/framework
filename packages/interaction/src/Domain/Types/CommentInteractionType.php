<?php

namespace RedJasmine\Interaction\Domain\Types;

use Illuminate\Support\Carbon;
use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Models\Records\InteractionRecordComment;
use Spatie\QueryBuilder\AllowedFilter;

class CommentInteractionType extends BaseInteractionType
{
    public function allowedFields() : array
    {
        return [
            AllowedFilter::exact('root_id'),
            AllowedFilter::exact('parent_id'),
        ];
    }


    public function getModelClass() : string
    {
        return InteractionRecordComment::class;
    }

    public function makeRecord(InteractionData $data) : InteractionRecordComment
    {

        $interactionRecord                   = InteractionRecordComment::make();
        $interactionRecord->resource_type    = $data->resourceType;
        $interactionRecord->resource_id      = $data->resourceId;
        $interactionRecord->interaction_type = $data->interactionType;
        $interactionRecord->quantity         = $data->quantity;
        $interactionRecord->owner            = $data->user;
        $interactionRecord->interaction_time = Carbon::now();
        $interactionRecord->content          = $data->extras['content'] ?? '';
        return $interactionRecord;
    }


}