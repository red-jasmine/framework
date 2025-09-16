<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 推广员仓库接口
 *
 * 提供推广员实体的读写操作统一接口
 *
 * @method Promoter find($id)
 */
interface PromoterRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据所有者查找推广员
     */
    public function findByOwner(UserInterface|Query $owner) : ?Promoter;
}
