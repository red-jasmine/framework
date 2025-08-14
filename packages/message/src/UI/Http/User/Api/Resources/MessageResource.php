<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 消息资源
 */
class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'biz' => $this->biz,
            'category_id' => $this->category_id,
            'receiver_id' => $this->receiver_id,
            'sender_id' => $this->sender_id,
            'template_id' => $this->template_id,
            'title' => $this->title,
            'content' => $this->content,
            'source' => $this->source?->value,
            'source_label' => $this->source?->label(),
            'type' => $this->type?->value,
            'type_label' => $this->type?->label(),
            'priority' => $this->priority?->value,
            'priority_label' => $this->priority?->label(),
            'priority_color' => $this->priority?->color(),
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'status_color' => $this->status?->color(),
            'push_status' => $this->push_status?->value,
            'push_status_label' => $this->push_status?->label(),
            'is_urgent' => $this->is_urgent,
            'is_burn_after_read' => $this->is_burn_after_read,
            'channels' => $this->channels,
            'read_at' => $this->read_at?->toISOString(),
            'expires_at' => $this->expires_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // 计算属性
            'is_read' => $this->status?->value === 'read',
            'is_unread' => $this->status?->value === 'unread',
            'is_archived' => $this->status?->value === 'archived',
            'is_expired' => $this->isExpired(),
            'is_high_priority' => $this->isHighPriority(),
            'has_attachments' => $this->getMessageContent()->hasAttachments(),
            'attachment_count' => $this->getMessageContent()->getAttachmentCount(),

            // 时间相关
            'created_at_human' => $this->created_at?->diffForHumans(),
            'read_at_human' => $this->read_at?->diffForHumans(),
            'expires_at_human' => $this->expires_at?->diffForHumans(),
            'time_to_expire' => $this->expires_at ? $this->expires_at->diffInHours(now()) : null,

            // 内容摘要
            'content_summary' => $this->getMessageContent()->getSummary(100),

            // 条件字段 - 分类信息
            'category' => $this->whenLoaded('category', function () {
                return new MessageCategoryResource($this->category);
            }),

            // 条件字段 - 模板信息
            'template' => $this->whenLoaded('template', function () {
                return new MessageTemplateResource($this->template);
            }),

            // 条件字段 - 推送日志
            'push_logs' => $this->whenLoaded('pushLogs', function () {
                return MessagePushLogResource::collection($this->pushLogs);
            }),

            // 扩展数据
            'data' => $this->when($this->data, function () {
                return [
                    'business_data' => $this->getMessageData()->businessData ?? [],
                    'template_variables' => $this->getMessageData()->templateVariables ?? [],
                    'attachments' => $this->getMessageData()->getExtension('attachments', []),
                    'push_parameters' => $this->getMessageData()->getExtension('push_parameters', []),
                ];
            }),
        ];
    }

    /**
     * 获取额外的元数据
     */
    public function with($request): array
    {
        return [
            'meta' => [
                'can_read' => $this->canRead(auth()->user()),
                'can_archive' => $this->status?->value !== 'archived',
                'should_burn' => $this->is_burn_after_read && $this->status?->value === 'read',
            ],
        ];
    }
}
