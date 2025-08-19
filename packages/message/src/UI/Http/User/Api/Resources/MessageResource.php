<?php

declare(strict_types = 1);

namespace RedJasmine\Message\UI\Http\User\Api\Resources;

use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 消息资源
 * @mixin Message
 */
class MessageResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id'                 => $this->id,
            'biz'                => $this->biz,
            'owner'              => $this->owner,
            'template_id'        => $this->template_id,
            'title'              => $this->title,
            'content'            => $this->getMessageContent(),
            'source'             => $this->source?->value,
            'source_label'       => $this->source?->label(),
            'type'               => $this->type?->value,
            'priority'           => $this->priority?->value,
            'status'             => $this->status,
            'is_urgent'          => $this->is_urgent,
            'is_burn_after_read' => $this->is_burn_after_read,
            'channels'           => $this->channels,
            'read_at'            => $this->read_at,
            'expires_at'         => $this->expires_at,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }

    /**
     * 获取额外的元数据
     */
    public function with($request) : array
    {
        return [
            'meta' => [
                'can_read'    => $this->canRead(auth()->user()),
                'can_archive' => $this->status?->value !== 'archived',
                'should_burn' => $this->is_burn_after_read && $this->status?->value === 'read',
            ],
        ];
    }
}
