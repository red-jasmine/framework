<?php

namespace RedJasmine\Invitation\Application\Services;

use RedJasmine\Invitation\Application\Services\Commands\CreateInvitationCodeCommandHandler;
use RedJasmine\Invitation\Application\Services\Commands\UseInvitationCodeCommandHandler;
use RedJasmine\Invitation\Domain\Data\InvitationCodeData;
use RedJasmine\Invitation\Domain\Data\UseInvitationCodeData;
use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Models\InvitationRecord;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeReadRepositoryInterface;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Invitation\Domain\Transformer\InvitationCodeTransformer;
use RedJasmine\Invitation\Exceptions\InvitationException;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 邀请码应用服务
 * 
 * 负责处理邀请码相关的业务逻辑，包括：
 * - 邀请码创建
 * - 邀请码使用
 * - 邀请链接生成
 * - 邀请统计查询
 * 
 * @see CreateInvitationCodeCommandHandler::handle()
 * @method InvitationCode create(InvitationCodeData $command)
 * @see UseInvitationCodeCommandHandler::handle()
 * @method InvitationRecord use(UseInvitationCodeData $command)
 * @method InvitationCode|null findByCode(string $code)
 * @method bool codeExists(string $code, int|null $excludeId = null)
 * @method string generateInvitationUrl(string $code, string $targetUrl, string|null $targetType = null)
 * @method array getUserInvitationStatistics(mixed $userId, mixed $userType)
 */
class InvitationCodeApplicationService extends ApplicationService
{
    /**
     * Hook前缀配置
     */
    public static string $hookNamePrefix = 'invitation.application.invitation-code';

    protected static string $modelClass = InvitationCode::class;

    public function __construct(
        public InvitationCodeRepositoryInterface $repository,
        public InvitationCodeReadRepositoryInterface $readRepository,
        public InvitationCodeTransformer $transformer
    ) {
    }

    public function getDefaultModelWithInfo(): array
    {
        return ['records'];
    }

    protected static $macros = [
        'create' => CreateInvitationCodeCommandHandler::class,
        'use' => UseInvitationCodeCommandHandler::class,
    ];

    /**
     * 根据邀请码查找
     */
    public function findByCode(string $code): ?InvitationCode
    {
        return $this->repository->findByCode($code);
    }

    /**
     * 检查邀请码是否存在
     */
    public function codeExists(string $code, ?int $excludeId = null): bool
    {
        return $this->repository->existsByCode($code);
    }

    /**
     * 生成邀请链接
     */
    public function generateInvitationUrl(string $code, string $targetUrl, ?string $targetType = null): string
    {
        $invitationCode = $this->findByCode($code);
        
        if (!$invitationCode) {
            throw new InvitationException('邀请码不存在');
        }

        return $invitationCode->generateInvitationUrl($targetUrl, $targetType);
    }

    /**
     * 获取用户邀请统计
     */
    public function getUserInvitationStatistics($userId, $userType): array
    {
        return $this->readRepository->getUserInvitationStatistics($userId, $userType);
    }
} 