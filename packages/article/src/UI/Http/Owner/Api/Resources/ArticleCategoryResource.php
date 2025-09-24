<?php

namespace RedJasmine\Article\UI\Http\Owner\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 文章分类资源
 */
class ArticleCategoryResource extends JsonResource
{
    /**
     * 转换资源为数组
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'parent_id' => $this->parent_id,
            'sort' => $this->sort,
            'is_show' => $this->is_show,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 关联数据
            'parent' => $this->whenLoaded('parent', function () {
                return new ArticleCategoryResource($this->parent);
            }),

            'children' => $this->whenLoaded('children', function () {
                return ArticleCategoryResource::collection($this->children);
            }),

            // 统计信息
            'articles_count' => $this->when(isset($this->articles_count), $this->articles_count),
        ];
    }
}
