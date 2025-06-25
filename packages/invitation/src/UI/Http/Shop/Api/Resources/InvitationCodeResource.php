<?php

namespace RedJasmine\Invitation\UI\Http\Shop\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 商家端邀请码资源
 */
class InvitationCodeResource extends JsonResource
{
    /**
     * 转换资源为数组
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'code_type' => $this->code_type,
            'name' => $this->name,
            'description' => $this->description,
            'max_usage' => $this->max_usage,
            'used_count' => $this->used_count,
            'remaining_count' => $this->remaining_count,
            'config' => $this->config,
            'target_type' => $this->target_type,
            'target_url' => $this->target_url,
            'expired_at' => $this->expired_at?->toDateTimeString(),
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            
            // 关联数据
            'records' => InvitationRecordResource::collection($this->whenLoaded('records')),
        ];
    }
} 