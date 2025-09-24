<?php

namespace RedJasmine\Article\UI\Http\Owner\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 文章资源
 */
class ArticleResource extends JsonResource
{
    /**
     * 转换资源为数组
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'image' => $this->image,
            'status' => $this->status,
            'status_label' => $this->status->label(),
            'is_top' => $this->is_top,
            'is_show' => $this->is_show,
            'publish_time' => $this->publish_time,
            'approval_status' => $this->approval_status,
            'approval_status_label' => $this->approval_status->label(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 关联数据
            'category' => $this->whenLoaded('category', function () {
                return new ArticleCategoryResource($this->category);
            }),

            'tags' => $this->whenLoaded('tags', function () {
                return ArticleTagResource::collection($this->tags);
            }),

            'extension' => $this->whenLoaded('extension', function () {
                return [
                    'content_type' => $this->extension->content_type,
                    'view_count' => $this->extension->view_count,
                    'like_count' => $this->extension->like_count,
                    'comment_count' => $this->extension->comment_count,
                ];
            }),

            // 操作权限
            'can_publish' => $this->canPublish(),
            'can_edit' => true,
            'can_delete' => true,
        ];
    }
}
