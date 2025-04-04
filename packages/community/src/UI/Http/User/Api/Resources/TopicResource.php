<?php

namespace RedJasmine\Community\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin Topic */
class TopicResource extends JsonResource
{
    public function toArray(Request $request) : array
    {


        return [
            'id'              => $this->id,
            'owner_type'      => $this->owner_type,
            'owner_id'        => $this->owner_id,
            'title'           => $this->title,
            'image'           => $this->image,
            'description'     => $this->description,
            'keywords'        => $this->keywords,
            'status'          => $this->status,
            'sort'            => $this->sort,
            'approval_status' => $this->approval_status,
            'version'         => $this->version,
            //'creator_type'    => $this->creator_type,
            //'creator_id'      => $this->creator_id,
            //'updater_type'    => $this->updater_type,
            //'updater_id'      => $this->updater_id,
            //'created_at'      => $this->created_at,
            //'updated_at'      => $this->updated_at,
            'category_id'     => $this->category_id,
            'category'        => new TopicCategoryResource($this->whenLoaded('category')),
            $this->mergeWhen($this->relationLoaded('content'),
                $this->relationLoaded('content') ? new TopicContentResource($this->whenLoaded('content')) : null),
        ];
    }
}
