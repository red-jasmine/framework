<?php

namespace RedJasmine\Announcement\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin AnnouncementCategory
 */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'biz' => $this->biz,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'cluster' => $this->cluster,
            'sort' => $this->sort,
            'icon' => $this->icon,
            'color' => $this->color,
            'is_leaf' => $this->is_leaf,
            'is_show' => $this->is_show,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            // 关联数据
            'children' => static::collection($this->whenLoaded('children')),
            'parent' => $this->whenLoaded('parent', function () {
                return new static($this->parent);
            }),

            // 统计信息
            'announcements_count' => $this->whenLoaded('announcements', function () {
                return $this->announcements->count();
            }),
        ];
    }
}
