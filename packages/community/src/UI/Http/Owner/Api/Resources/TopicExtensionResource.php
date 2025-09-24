<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Community\Domain\Models\Extensions\TopicExtension;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin TopicExtension */
class TopicExtensionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content_type' => $this->content_type,
            'content_type_label' => $this->content_type->label(),
            'view_count' => $this->view_count,
            'like_count' => $this->like_count,
            'comment_count' => $this->comment_count,
            'share_count' => $this->share_count,
            'favorite_count' => $this->favorite_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
