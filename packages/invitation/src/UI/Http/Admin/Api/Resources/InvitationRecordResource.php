<?php

namespace RedJasmine\Invitation\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 邀请记录资源
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
            'inviter' => [
                'type' => $this->inviter_type,
                'id' => $this->inviter_id,
                'nickname' => $this->inviter_nickname,
            ],
            'invitee' => [
                'type' => $this->invitee_type,
                'id' => $this->invitee_id,
                'nickname' => $this->invitee_nickname,
            ],
            'ip' => $this->ip,
            'user_agent' => $this->user_agent,
            'extend_data' => $this->extend_data,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            
            // 关联数据
            'invitation_code' => new InvitationCodeResource($this->whenLoaded('invitationCode')),
        ];
    }
} 