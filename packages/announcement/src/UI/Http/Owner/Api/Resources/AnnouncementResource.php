<?php

namespace RedJasmine\Announcement\UI\Http\Owner\Api\Resources;

use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin Announcement
 */
class AnnouncementResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'biz' => $this->biz,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'image' => $this->image,
            'content_type' => $this->content_type,
            'content' => $this->content,
            'publish_time' => $this->publish_time,
            'status' => $this->status,
            'approval_status' => $this->approval_status,
            'attachments' => $this->attachments,
            'is_force_read' => $this->is_force_read,
            'scopes' => $this->scopes,
            'channels' => $this->channels,
            'approval_comment' => $this->approval_comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            // 条件字段
            'category' => $this->whenLoaded('category', function () {
                return new CategoryResource($this->category);
            }),

            // 审批信息
            'approval' => $this->whenLoaded('approval', function () {
                return [
                    'status' => $this->approval->status,
                    'comment' => $this->approval->comment,
                    'approved_at' => $this->approval->approved_at,
                    'approved_by' => $this->approval->approved_by,
                ];
            }),
        ];
    }
}
