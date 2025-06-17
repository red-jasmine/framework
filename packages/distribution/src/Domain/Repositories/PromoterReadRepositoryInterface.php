<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface PromoterReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 根据ID查找分销员
     */
    public function findById(string $id): ?Promoter;

    /**
     * 根据用户ID查找分销员
     */
    public function findByUserId(string $userId): ?Promoter;

    /**
     * 根据推广上级ID查找分销员列表
     */
    public function findByParentId(string $parentId): array;

}