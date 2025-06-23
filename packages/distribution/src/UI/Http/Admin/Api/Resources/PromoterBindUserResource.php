<?php

namespace RedJasmine\Distribution\UI\Http\Admin\Api\Resources;

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
            'user_type' => $this->user_type,
            'user_id' => $this->user_id,
            'promoter_id' => $this->promoter_id,
            'status' => $this->status,
            'status_label' => $this->status->getLabel(),
            'bind_time' => $this->bind_time?->format('Y-m-d H:i:s'),
            'protection_time' => $this->protection_time?->format('Y-m-d H:i:s'),
            'expiration_time' => $this->expiration_time?->format('Y-m-d H:i:s'),
            'bind_reason' => $this->bind_reason,
            'invitation_code' => $this->invitation_code,
            'unbind_reason' => $this->unbind_reason,
            'unbind_time' => $this->unbind_time?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            
            // 关联数据
            'promoter' => $this->whenLoaded('promoter'),
        ];
    }
} 