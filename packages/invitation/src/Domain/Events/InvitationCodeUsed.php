<?php

namespace RedJasmine\Invitation\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Models\InvitationUsageLog;

/**
 * 邀请码使用事件
 */
class InvitationCodeUsed
{
    use Dispatchable;

    public function __construct(
        public InvitationCode $invitationCode,
        public InvitationUsageLog $usageLog
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
            'usage_log_id' => $this->usageLog->id,
            'user_type' => $this->usageLog->user_type,
            'user_id' => $this->usageLog->user_id,
            'used_at' => $this->usageLog->used_at,
            'context' => $this->usageLog->context,
        ];
    }
} 