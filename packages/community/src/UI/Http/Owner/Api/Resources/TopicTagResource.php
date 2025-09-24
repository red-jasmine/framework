<?php

namespace RedJasmine\Community\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Community\Domain\Models\TopicTag;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin TopicTag */
class TopicTagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'icon' => $this->icon,
            'sort' => $this->sort,
            'status' => $this->status,
            'status_label' => $this->status->label(),
            'status_color' => $this->status->color(),
            'version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 统计信息
            'topics_count' => $this->when(isset($this->topics_count), $this->topics_count),
        ];
    }
}
