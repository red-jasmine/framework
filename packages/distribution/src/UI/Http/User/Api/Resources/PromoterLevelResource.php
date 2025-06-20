<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Distribution\Domain\Models\PromoterLevel;

/** @mixin PromoterLevel */
class PromoterLevelResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'            => $this->id,
            'level'         => $this->level,
            'name'          => $this->name,
            'description'   => $this->description,
            'icon'          => $this->icon,
            'image'         => $this->image,
            'product_ratio' => $this->product_ratio,
            'parent_ratio'  => $this->parent_ratio,
            'upgrades'      => $this->upgrades,
            'keeps'         => $this->keeps,
            'benefits'      => $this->benefits,
        ];
    }
}
