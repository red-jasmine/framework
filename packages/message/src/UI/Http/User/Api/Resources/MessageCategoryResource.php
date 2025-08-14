<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 消息分类资源
 */
class MessageCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'biz' => $this->biz,
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'sort' => $this->sort,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'status_color' => $this->status?->color(),
            'is_system' => $this->is_system,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // 计算属性
            'is_enabled' => $this->status?->value === 'enable',
            'is_root' => $this->parent_id === null,
            'level' => $this->getLevel(),

            // 统计信息
            'messages_count' => $this->whenCounted('messages'),
            'children_count' => $this->whenCounted('children'),

            // 条件字段 - 父分类
            'parent' => $this->whenLoaded('parent', function () {
                return new self($this->parent);
            }),

            // 条件字段 - 子分类
            'children' => $this->whenLoaded('children', function () {
                return self::collection($this->children);
            }),

            // 条件字段 - 消息列表
            'messages' => $this->whenLoaded('messages', function () {
                return MessageResource::collection($this->messages);
            }),

            // 路径信息
            'path' => $this->when($this->relationLoaded('parent'), function () {
                return $this->getCategoryPath();
            }),
        ];
    }

    /**
     * 获取分类层级
     */
    protected function getLevel(): int
    {
        $level = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }
        
        return $level;
    }

    /**
     * 获取分类路径
     */
    protected function getCategoryPath(): array
    {
        $path = [];
        $category = $this;
        
        while ($category) {
            array_unshift($path, [
                'id' => $category->id,
                'name' => $category->name,
            ]);
            $category = $category->parent;
        }
        
        return $path;
    }

    /**
     * 获取额外的元数据
     */
    public function with($request): array
    {
        return [
            'meta' => [
                'can_edit' => !$this->is_system && $this->owner_id === auth()->id(),
                'can_delete' => !$this->is_system && $this->owner_id === auth()->id() && $this->messages_count === 0,
                'has_children' => $this->children_count > 0,
                'has_messages' => $this->messages_count > 0,
            ],
        ];
    }
}
