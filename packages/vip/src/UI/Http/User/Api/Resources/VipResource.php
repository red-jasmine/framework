<?php

namespace RedJasmine\Vip\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;
use RedJasmine\Vip\Domain\Models\Vip;

/** @mixin Vip */
class VipResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'          => $this->id,
            'app_id'      => $this->app_id,
            'type'        => $this->type,
            'level'       => $this->level,
            'name'        => $this->name,
            'icon'        => $this->icon,
            'description' => $this->description,
            'status'      => $this->status,
            'version'     => $this->version,
        ];
    }
}
