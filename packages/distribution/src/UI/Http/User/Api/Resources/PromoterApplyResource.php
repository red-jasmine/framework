<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin PromoterApply */
class PromoterApplyResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'              => $this->id,
            'apply_type'      => $this->apply_type,
            'apply_method'    => $this->apply_method,
            'approval_method' => $this->approval_method,
            'approval_status' => $this->approval_status,
            'apply_at'        => $this->apply_at,
            'approval_at'     => $this->approval_at,
            'approval_reason' => $this->approval_reason,
            'extra'           => $this->extra,
            'deleted_at'      => $this->deleted_at,
            'promoterLevel'   => new PromoterLevelResource($this->whenLoaded('promoterLevel')),
        ];
    }
}
