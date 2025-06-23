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
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'promoter_id' => $this->promoter_id,
            'status' => $this->status,
            'status_label' => $this->status->getLabel(),
            'bind_time' => $this->bind_time?->format('Y-m-d H:i:s'),
            'protection_time' => $this->protection_time?->format('Y-m-d H:i:s'),
            'expiration_time' => $this->expiration_time?->format('Y-m-d H:i:s'),
            'bind_reason' => $this->bind_reason,
            'invitation_code' => $this->invitation_code,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            
            // 关联数据
            'promoter' => $this->whenLoaded('promoter'),
        ];
    }
} 