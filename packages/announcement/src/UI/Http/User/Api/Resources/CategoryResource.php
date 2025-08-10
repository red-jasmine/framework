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
            'is_leaf'     => $this->is_leaf,
            'is_show'     => $this->is_show,
            //'status'       => $this->status,
            //'version'      => $this->version,
            //'creator_type' => $this->creator_type,
            //'creator_id'   => $this->creator_id,
            //'updater_type' => $this->updater_type,
            //'updater_id'   => $this->updater_id,
            //'created_at'   => $this->created_at,
            //'updated_at'   => $this->updated_at,
            'children'    => static::collection(collect($this->children)),
            'parent'      => new static($this->whenLoaded('parent')),
        ];
    }
}
