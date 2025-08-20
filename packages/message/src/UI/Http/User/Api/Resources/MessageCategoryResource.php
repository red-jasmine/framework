<?php

declare(strict_types = 1);

namespace RedJasmine\Message\UI\Http\User\Api\Resources;

use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 消息分类资源
 * @mixin MessageCategory
 */
class MessageCategoryResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'biz'         => $this->biz,
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'image'       => $this->image,
            'cluster'     => $this->cluster,
            'sort'        => $this->sort,
            'icon'        => $this->icon,
            'color'       => $this->color,
            'is_leaf'     => $this->is_leaf,
            'is_show'     => $this->is_show,
            'children'    => static::collection(collect($this->children)),
            'parent'      => new static($this->whenLoaded('parent')),

            'count' => $this->count ?? 0,


        ];
    }

    /**
     * 获取分类层级
     */
    protected function getLevel() : int
    {
        $level  = 0;
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
    protected function getCategoryPath() : array
    {
        $path     = [];
        $category = $this;

        while ($category) {
            array_unshift($path, [
                'id'   => $category->id,
                'name' => $category->name,
            ]);
            $category = $category->parent;
        }

        return $path;
    }

    /**
     * 获取额外的元数据
     */
    public function with($request) : array
    {
        return [
            'meta' => [
                'can_edit'     => !$this->is_system && $this->owner_id === auth()->id(),
                'can_delete'   => !$this->is_system && $this->owner_id === auth()->id() && $this->messages_count === 0,
                'has_children' => $this->children_count > 0,
                'has_messages' => $this->messages_count > 0,
            ],
        ];
    }
}
