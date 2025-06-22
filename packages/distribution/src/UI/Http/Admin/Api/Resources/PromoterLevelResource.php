<?php

namespace RedJasmine\Distribution\UI\Http\Admin\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class PromoterLevelResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'level' => $this->level,
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'image' => $this->image,
            'upgrades' => $this->upgrades,
            'keeps' => $this->keeps,
            'product_ratio' => $this->productRatio,
            'parent_ratio' => $this->parentRatio,
            'benefits' => $this->benefits,
            'apply_method' => $this->apply_method,
            'approval_method' => $this->approval_method,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}