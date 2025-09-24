<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin TopicCategory */
class TopicCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'cluster' => $this->cluster,
            'sort' => $this->sort,
            'is_leaf' => $this->is_leaf,
            'is_show' => $this->is_show,
            'status' => $this->status,
            'status_label' => $this->status->label(),
            'status_color' => $this->status->color(),
            'version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 树形结构
            'parent_id' => $this->parent_id,
            'parent' => $this->whenLoaded('parent', function () {
                return new static($this->parent);
            }),

            'children' => $this->whenLoaded('children', function () {
                return static::collection($this->children);
            }),

            // 统计信息
            'topics_count' => $this->when(isset($this->topics_count), $this->topics_count),
        ];
    }
}
