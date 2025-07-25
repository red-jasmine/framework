<?php

namespace RedJasmine\Invitation\Infrastructure\Repositories\Eloquent;

use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Repositories\InvitationCodeRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

/**
 * 邀请码Eloquent仓库实现
 */
class InvitationCodeRepository extends EloquentRepository implements InvitationCodeRepositoryInterface
{
    protected static string $eloquentModelClass = InvitationCode::class;

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