<?php

namespace RedJasmine\Interaction\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin InteractionRecord */
class InteractionRecordResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'               => $this->id,
            'resource_type'    => $this->resource_type,
            'resource_id'      => $this->resource_id,
            'interaction_type' => $this->interaction_type,
            'interaction_time' => $this->interaction_time,
            'quantity'         => $this->quantity,
            'user_type'        => $this->user_type,
            'user_id'          => $this->user_id,

            $this->merge($this->getExtras())


        ];
    }
}
