<?php

namespace RedJasmine\Invitation\UI\Http\User\Api\Resources;


use Illuminate\Http\Request;
use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 用户端邀请码资源
 */

/**
 * @mixin InvitationCode
 */
class InvitationCodeResource extends JsonResource
{
    /**
     * 转换资源为数组
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray(Request $request) : array
    {
        return [
            'id'          => $this->id,
            'code'        => $this->code,
            'code_type'   => $this->code_type,
            'description' => $this->description,
            'max_usage'   => $this->max_usage,
            'used_count'  => $this->used_count,
            'expired_at'  => $this->expired_at?->toDateTimeString(),
            'status'      => $this->status,
            'created_at'  => $this->created_at->toDateTimeString(),
            'updated_at'  => $this->updated_at->toDateTimeString(),


        ];
    }
} 