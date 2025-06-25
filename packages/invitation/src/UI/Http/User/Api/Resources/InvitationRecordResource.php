<?php

namespace RedJasmine\Invitation\UI\Http\User\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 用户端邀请记录资源
 */
class InvitationRecordResource extends JsonResource
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
            'invitation_code_id' => $this->invitation_code_id,
            'invitee' => [
                'type' => $this->invitee_type,
                'id' => $this->invitee_id,
                'nickname' => $this->invitee_nickname,
            ],
            'extend_data' => $this->extend_data,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
} 