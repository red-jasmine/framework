<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Distribution\Domain\Models\PromoterTeam;

/** @mixin PromoterTeam */
class PromoterTeamResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'          => (string) $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'cluster'     => $this->cluster,
            'sort'        => $this->sort,
            'is_leaf'     => $this->is_leaf,
            'is_show'     => $this->is_show,
            'status'      => $this->status,
            'image'       => $this->image,
            'icon'        => $this->icon,
            'color'       => $this->color,
            'extra'       => $this->extra,
            'leader_id'   => $this->leader_id,
        ];
    }
}
