<?php

namespace RedJasmine\Announcement\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin AnnouncementCategory
 */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'biz'         => $this->biz,
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'image'       => $this->image,
            'cluster'     => $this->cluster,
            'sort'        => $this->sort,
            'icon'        => $this->icon,
            'color'       => $this->color,
            'is_leaf'     => $this->is_leaf,
            'is_show'     => $this->is_show,
            'children'    => static::collection(collect($this->children)),
            'parent'      => new static($this->whenLoaded('parent')),
        ];
    }
}
