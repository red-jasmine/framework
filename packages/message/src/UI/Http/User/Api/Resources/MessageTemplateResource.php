<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 消息模板资源
 */
class MessageTemplateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'biz' => $this->biz,
            'code' => $this->code,
            'name' => $this->name,
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type?->value,
            'type_label' => $this->type?->label(),
            'description' => $this->description,
            'variables' => $this->variables,
            'sort' => $this->sort,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'status_color' => $this->status?->color(),
            'is_system' => $this->is_system,
            'usage_count' => $this->usage_count,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // 计算属性
            'is_enabled' => $this->status?->value === 'enable',
            'is_active' => $this->isEnabled(),
            'has_variables' => !empty($this->variables),
            'variable_count' => count($this->variables ?? []),

            // 时间相关
            'created_at_human' => $this->created_at?->diffForHumans(),
            'updated_at_human' => $this->updated_at?->diffForHumans(),
            'last_used_at' => $this->last_used_at?->toISOString(),
            'last_used_at_human' => $this->last_used_at?->diffForHumans(),

            // 统计信息
            'messages_count' => $this->whenCounted('messages'),

            // 条件字段 - 分类信息
            'category' => $this->whenLoaded('category', function () {
                return new MessageCategoryResource($this->category);
            }),

            // 条件字段 - 消息列表
            'messages' => $this->whenLoaded('messages', function () {
                return MessageResource::collection($this->messages);
            }),

            // 变量详情
            'variable_details' => $this->when($this->variables, function () {
                return collect($this->variables)->map(function ($variable) {
                    return [
                        'name' => $variable['name'] ?? '',
                        'label' => $variable['label'] ?? $variable['name'] ?? '',
                        'type' => $variable['type'] ?? 'string',
                        'required' => $variable['required'] ?? false,
                        'default' => $variable['default'] ?? null,
                        'description' => $variable['description'] ?? '',
                        'validation' => $variable['validation'] ?? [],
                    ];
                })->values()->toArray();
            }),

            // 内容预览
            'content_preview' => $this->when(
                strlen($this->content) > 200,
                fn() => substr(strip_tags($this->content), 0, 200) . '...'
            ),
        ];
    }

    /**
     * 获取额外的元数据
     */
    public function with($request): array
    {
        return [
            'meta' => [
                'can_edit' => !$this->is_system && $this->owner_id === auth()->id(),
                'can_delete' => !$this->is_system && $this->owner_id === auth()->id(),
                'can_duplicate' => true,
                'can_preview' => true,
                'is_popular' => $this->usage_count > 100,
                'usage_level' => $this->getUsageLevel(),
            ],
        ];
    }

    /**
     * 获取使用频率等级
     */
    protected function getUsageLevel(): string
    {
        $count = $this->usage_count;
        
        if ($count === 0) {
            return 'unused';
        } elseif ($count < 10) {
            return 'low';
        } elseif ($count < 50) {
            return 'medium';
        } elseif ($count < 100) {
            return 'high';
        } else {
            return 'very_high';
        }
    }
}
