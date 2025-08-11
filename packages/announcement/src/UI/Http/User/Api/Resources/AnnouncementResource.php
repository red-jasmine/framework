<?php

namespace RedJasmine\Announcement\UI\Http\User\Api\Resources;

use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin  Announcement
 */
class AnnouncementResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id'            => $this->id,
            'biz'           => $this->biz,
            'category_id'   => $this->category_id,
            'title'         => $this->title,
            'image'         => $this->image,
            'content_type'  => $this->content_type,
            'content'       => $this->content,
            'publish_time'  => $this->publish_time,
            'status'        => $this->status,
            'attachments'   => $this->attachments,
            'is_force_read' => $this->is_force_read,
            // 条件字段
            'category'      => $this->whenLoaded('category', function () {
                return new CategoryResource($this->category);
            }),
        ];
    }
}
