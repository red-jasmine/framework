<?php

namespace RedJasmine\Article\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Article\Domain\Models\ArticleTag;

/** @mixin ArticleTag */
class ArticleTagResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'owner_type'   => $this->owner_type,
            'owner_id'     => $this->owner_id,
            'name'         => $this->name,
            'description'  => $this->description,
            'icon'         => $this->icon,
            'color'        => $this->color,
            'cluster'      => $this->cluster,
            'sort'         => $this->sort,
            'is_show'      => $this->is_show,
            'is_public'    => $this->is_public,
            'status'       => $this->status,
            'version'      => $this->version,
            'creator_type' => $this->creator_type,
            'creator_id'   => $this->creator_id,
            'updater_type' => $this->updater_type,
            'updater_id'   => $this->updater_id,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
