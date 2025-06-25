<?php

namespace RedJasmine\Invitation\Domain\Data;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class UseInvitationCodeData extends Data
{
    public string $code;

    public UserInterface $invitee;

    public ?array $context = null;

    public ?string $targetUrl = null;

    public ?string $targetType = null;

    public ?UserInterface $operator = null;

    /**
     * 获取邀请渠道/来源
     */
    public function getSource(): ?string
    {
        return $this->context['source'] ?? null;
    }

    /**
     * 获取邀请设备信息
     */
    public function getDeviceInfo(): ?array
    {
        return $this->context['device'] ?? null;
    }

    /**
     * 获取邀请IP
     */
    public function getIpAddress(): ?string
    {
        return $this->context['ip'] ?? null;
    }

    /**
     * 设置上下文信息
     */
    public function setContext(array $context): void
    {
        $this->context = array_merge($this->context ?? [], $context);
    }

    /**
     * 添加单个上下文项
     */
    public function addContextItem(string $key, mixed $value): void
    {
        if ($this->context === null) {
            $this->context = [];
        }
        
        $this->context[$key] = $value;
    }
} 