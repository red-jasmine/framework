<?php

namespace RedJasmine\Invitation\Domain\Repositories;

use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 邀请码仓库接口
 * 
 * @method InvitationCode find($id)
 */
interface InvitationCodeRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据邀请码查找
     */
    public function findByCode(string $code): ?InvitationCode;

    /**
     * 检查邀请码是否存在
     */
    public function existsByCode(string $code): bool;
} 