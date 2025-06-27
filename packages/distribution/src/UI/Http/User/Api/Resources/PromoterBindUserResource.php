<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin PromoterBindUser
 */
class PromoterBindUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'id'              => $this->id,
            'promoter_id'     => $this->promoter_id,
            'status'          => $this->status,
            'status_label'    => $this->status->getLabel(),
            'bound_time'      => $this->bound_time,
            'activation_time' => $this->activation_time,
            'unbound_time'    => $this->unbound_time,
            'unbound_type'    => $this->unbound_type,
            'protection_time' => $this->protection_time,
            'expiration_time' => $this->expiration_time,
        ];
    }
} 