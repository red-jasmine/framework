<?php

namespace RedJasmine\Vip\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;
use RedJasmine\Vip\Domain\Models\UserVip;

/** @mixin UserVip */
class UserVipResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'         => $this->id,
            'owner_type' => $this->owner_type,
            'owner_id'   => $this->owner_id,
            'app_id'     => $this->app_id,
            'type'       => $this->type,
            'level'      => $this->level,
            'start_time' => $this->start_time?->format('Y-m-d'),
            'end_time'   => $this->end_time?->format('Y-m-d'),
            'is_forever' => $this->is_forever,
            'version'    => $this->version,
            'vip'        => new VipResource($this->whenLoaded('vip')),
        ];
    }
}
