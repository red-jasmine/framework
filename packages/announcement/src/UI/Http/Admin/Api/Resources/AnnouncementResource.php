<?php

namespace RedJasmine\Announcement\UI\Http\Admin\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'biz' => $this->biz,
            'owner_type' => $this->owner_type,
            'owner_id' => $this->owner_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'cover' => $this->cover,
            'content' => $this->content,
            'scopes' => $this->scopes,
            'channels' => $this->channels,
            'publish_time' => $this->publish_time,
            'status' => $this->status,
            'attachments' => $this->attachments,
            'approval_status' => $this->approval_status,
            'is_force_read' => $this->is_force_read,
            'version' => $this->version,
            'creator_type' => $this->creator_type,
            'creator_id' => $this->creator_id,
            'updater_type' => $this->updater_type,
            'updater_id' => $this->updater_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 条件字段
            'category' => $this->whenLoaded('category', function () {
                return new CategoryResource($this->category);
            }),
        ];
    }
}
