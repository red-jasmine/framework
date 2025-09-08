<?php

namespace RedJasmine\Invitation\Infrastructure\Repositories\Eloquent;

use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

/**
 * 邀请码Eloquent仓库实现
 */
class InvitationCodeRepository extends Repository implements InvitationCodeRepositoryInterface
{
    protected static string $modelClass = InvitationCode::class;

    /**
     * 根据邀请码查找
     */
    public function findByCode(string $code): ?InvitationCode
    {
        return InvitationCode::where('code', $code)->first();
    }

    /**
     * 检查邀请码是否存在
     */
    public function existsByCode(string $code): bool
    {
        return InvitationCode::where('code', $code)->exists();
    }
} 