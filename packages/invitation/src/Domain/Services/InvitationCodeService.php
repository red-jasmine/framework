<?php

namespace RedJasmine\Invitation\Domain\Services;

use Illuminate\Support\Carbon;
use RedJasmine\Invitation\Domain\Data\InvitationCodeData;
use RedJasmine\Invitation\Domain\Models\Enums\CodeStatus;
use RedJasmine\Invitation\Domain\Models\Enums\GenerateType;
use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Models\InvitationUsageLog;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Invitation\Exceptions\InvitationCodeException;
use RedJasmine\Invitation\Infrastructure\Services\InvitationCodeGenerator;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * 邀请码领域服务
 */
class InvitationCodeService extends Service
{
    public function __construct(
        protected InvitationCodeRepositoryInterface $repository,
        protected InvitationCodeGenerator $codeGenerator
    ) {
    }

    /**
     * 生成邀请码
     */
    public function generateCode(InvitationCodeData $data): InvitationCode
    {
        // 检查用户是否已有有效邀请码
        if ($this->hasActiveCode($data->inviter)) {
            throw new InvitationCodeException('用户已存在有效的邀请码');
        }

        $invitationCode = new InvitationCode();
        
        // 生成邀请码
        $code = $data->generateType === GenerateType::CUSTOM 
            ? $data->code 
            : $this->codeGenerator->generate();

        // 检查邀请码是否重复
        if ($this->repository->findByCode($code)) {
            throw new InvitationCodeException('邀请码已存在');
        }

        // 将UserInterface转换为Inviter值对象
        $inviter = new \RedJasmine\Invitation\Domain\Models\ValueObjects\Inviter(
            get_class($data->inviter),
            $data->inviter->id,
            $data->inviter->name ?? $data->inviter->id
        );

        $invitationCode->setCode($code)
            ->setInviter($inviter)
            ->setGenerateType($data->generateType)
            ->setExpiredAt($data->expiredAt)
            ->setMaxUsages($data->maxUsages)
            ->setStatus(CodeStatus::ACTIVE);

        return $invitationCode;
    }

    /**
     * 使用邀请码
     */
    public function useCode(string $code, UserInterface $user, array $context = []): InvitationUsageLog
    {
        $invitationCode = $this->repository->findByCode($code);
        
        if (!$invitationCode) {
            throw new InvitationCodeException('邀请码不存在');
        }

        // 检查邀请码状态
        if (!$invitationCode->isActive()) {
            throw new InvitationCodeException('邀请码已失效');
        }

        // 检查是否过期
        if ($invitationCode->isExpired()) {
            throw new InvitationCodeException('邀请码已过期');
        }

        // 检查使用次数限制
        if ($invitationCode->isMaxUsagesReached()) {
            throw new InvitationCodeException('邀请码使用次数已达上限');
        }

        // 检查用户是否已使用过此邀请码
        if ($invitationCode->hasBeenUsedBy($user)) {
            throw new InvitationCodeException('用户已使用过此邀请码');
        }

        // 记录使用日志
        $usageLog = $invitationCode->recordUsage($user, $context);

        return $usageLog;
    }

    /**
     * 检查用户是否有有效邀请码
     */
    protected function hasActiveCode(UserInterface $user): bool
    {
        return $this->repository->findActiveByInviter($user) !== null;
    }

    /**
     * 禁用邀请码
     */
    public function disableCode(InvitationCode $invitationCode): InvitationCode
    {
        if (!$invitationCode->isActive()) {
            throw new InvitationCodeException('邀请码已被禁用');
        }

        $invitationCode->disable();
        
        return $invitationCode;
    }

    /**
     * 启用邀请码
     */
    public function enableCode(InvitationCode $invitationCode): InvitationCode
    {
        if ($invitationCode->isActive()) {
            throw new InvitationCodeException('邀请码已是启用状态');
        }

        $invitationCode->enable();
        
        return $invitationCode;
    }

    /**
     * 延期邀请码
     */
    public function extendExpiration(InvitationCode $invitationCode, Carbon $newExpiredAt): InvitationCode
    {
        if ($invitationCode->expired_at && $newExpiredAt->lte($invitationCode->expired_at)) {
            throw new InvitationCodeException('新的过期时间必须晚于当前过期时间');
        }

        $invitationCode->setExpiredAt($newExpiredAt);
        
        return $invitationCode;
    }
} 