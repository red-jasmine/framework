<?php

namespace RedJasmine\Card\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin \RedJasmine\Card\Domain\Models\Card
 */
class CardResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'owner_type'   => $this->owner_type,
            'owner_id'     => $this->owner_id,
            'is_loop'      => $this->is_loop,
            'status'       => $this->status,
            'content'      => $this->content,
            'remarks'      => $this->remarks,
            'sold_time'    => $this->sold_time,
            'creator_type' => $this->creator_type,
            'creator_id'   => $this->creator_id,
            'updater_type' => $this->updater_type,
            'updater_id'   => $this->updater_id,
            'created_at'   => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updated_at?->format('Y-m-d H:i:s'),
            'group'        => CardGroupResource::make($this->whenLoaded('group')),

        ];
    }


}
