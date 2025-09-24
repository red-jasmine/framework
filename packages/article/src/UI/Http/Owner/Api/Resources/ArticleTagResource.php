<?php

namespace RedJasmine\Article\UI\Http\Owner\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 文章标签资源
 */
class ArticleTagResource extends JsonResource
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
            'color' => $this->color,
            'sort' => $this->sort,
            'is_show' => $this->is_show,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 统计信息
            'articles_count' => $this->when(isset($this->articles_count), $this->articles_count),
        ];
    }
}
