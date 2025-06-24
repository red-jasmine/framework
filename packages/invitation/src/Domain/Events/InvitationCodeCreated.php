<?php

namespace RedJasmine\Invitation\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Invitation\Domain\Models\InvitationCode;

/**
 * 邀请码创建事件
 */
class InvitationCodeCreated
{
    use Dispatchable;

    public function __construct(
        public InvitationCode $invitationCode
    ) {
    }

    /**
     * 获取事件数据
     */
    public function getData(): array
    {
        return [
            'invitation_code_id' => $this->invitationCode->id,
            'code' => $this->invitationCode->code,
            'inviter_type' => $this->invitationCode->inviter->type,
            'inviter_id' => $this->invitationCode->inviter->id,
            'generate_type' => $this->invitationCode->generate_type->value,
            'created_at' => $this->invitationCode->created_at,
        ];
    }
} 