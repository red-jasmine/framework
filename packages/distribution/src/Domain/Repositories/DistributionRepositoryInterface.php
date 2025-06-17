<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 分销领域基础仓库接口
 * 定义分销领域内所有仓库的通用契约
 */
interface DistributionRepositoryInterface extends RepositoryInterface
{
    /**
     * 获取领域模型类名
     */
    public function getModelClass(): string;

    /**
     * 事务处理
     */
    public function transaction(callable $callback);
}