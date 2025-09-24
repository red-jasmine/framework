<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin Topic */
class TopicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'status' => $this->status,
            'status_label' => $this->status->label(),
            'status_color' => $this->status->color(),
            'sort' => $this->sort,
            'approval_status' => $this->approval_status,
            'approval_status_label' => $this->approval_status->label(),
            'approval_status_color' => $this->approval_status->color(),
            'is_best' => $this->is_best,
            'version' => $this->version,
            'publish_time' => $this->publish_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            // 关联数据
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', function () {
                return new TopicCategoryResource($this->category);
            }),

            'tags' => $this->whenLoaded('tags', function () {
                return TopicTagResource::collection($this->tags);
            }),

            'extension' => $this->whenLoaded('extension', function () {
                return new TopicExtensionResource($this->extension);
            }),

            // 所有者信息
            'owner_type' => $this->owner_type,
            'owner_id' => $this->owner_id,

            // 操作者信息
            'creator_type' => $this->creator_type,
            'creator_id' => $this->creator_id,
            'updater_type' => $this->updater_type,
            'updater_id' => $this->updater_id,
        ];
    }
}
