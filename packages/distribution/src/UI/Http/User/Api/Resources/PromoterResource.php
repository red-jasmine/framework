<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin Promoter */
class PromoterResource extends JsonResource
{
    public function toArray(Request $request) : array
    {

        return [
            'id'            => $this->id,
            'owner_type'    => $this->owner_type,
            'owner_id'      => $this->owner_id,
            'level'         => $this->level,
            'parent_id'     => $this->parent_id,
            'team_id'       => $this->team_id,
            'name'          => $this->name,
            'remarks'       => $this->remarks,
            'status'        => $this->status,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'parent'        => new PromoterResource($this->whenLoaded('parent')),
            'promoterLevel' => new PromoterLevelResource($this->whenLoaded('promoterLevel')),
            'team'          => new PromoterTeamResource($this->whenLoaded('team')),
        ];
    }
}