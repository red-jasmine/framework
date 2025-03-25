<?php

namespace RedJasmine\Article\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Article\Domain\Models\ArticleCategory;

/** @mixin ArticleCategory */
class ArticleCategoryResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'description'  => $this->description,
            'image'        => $this->image,
            'cluster'      => $this->cluster,
            'sort'         => $this->sort,
            'is_leaf'      => $this->is_leaf,
            'is_show'      => $this->is_show,
            'status'       => $this->status,
            'version'      => $this->version,
            'creator_type' => $this->creator_type,
            'creator_id'   => $this->creator_id,
            'updater_type' => $this->updater_type,
            'updater_id'   => $this->updater_id,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'parent'       => new static($this->whenLoaded('parent')),
        ];
    }
}
