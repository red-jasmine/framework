<?php

namespace RedJasmine\Announcement\UI\Http\Admin\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'biz' => $this->biz,
            'owner_type' => $this->owner_type,
            'owner_id' => $this->owner_id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'description' => $this->description,
            'sort' => $this->sort,
            'is_show' => $this->is_show,
            'version' => $this->version,
            'creator_type' => $this->creator_type,
            'creator_id' => $this->creator_id,
            'updater_type' => $this->updater_type,
            'updater_id' => $this->updater_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 条件字段
            'parent' => $this->whenLoaded('parent', function () {
                return new CategoryResource($this->parent);
            }),
            
            // 关联资源
            'children' => CategoryResource::collection($this->whenLoaded('children')),
            'announcements' => AnnouncementResource::collection($this->whenLoaded('announcements')),
        ];
    }
}
