<?php

namespace RedJasmine\Invitation\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Invitation\Domain\Models\InvitationCode;

/**
 * 邀请码资源
 */

/**
 * @mixin InvitationCode
 */
class InvitationCodeResource extends JsonResource
{
    /**
     * 转换资源为数组
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray(Request $request) : array
    {
        return [
            'id'              => $this->id,
            'code'            => $this->code,
            'code_type'       => $this->code_type,

            'description'     => $this->description,
            'max_usage'       => $this->max_usage,
            'used_count'      => $this->used_count,
            'remaining_count' => $this->remaining_count,
            'config'          => $this->config,
            'expired_at'      => $this->expired_at?->toDateTimeString(),
            'status'          => $this->status,
            'created_at'      => $this->created_at->toDateTimeString(),
            'updated_at'      => $this->updated_at->toDateTimeString(),
            'deleted_at'      => $this->deleted_at?->toDateTimeString(),
        ];
    }
} 