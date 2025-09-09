<?php

namespace RedJasmine\Promotion\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 活动仓库接口
 *
 * 提供活动实体的读写操作统一接口
 *
 * @method Activity find($id)
 */
interface ActivityRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据类型查找活动
     */
    public function findByType(string $type): Collection;
    
    /**
     * 查找正在进行的活动
     */
    public function findRunningActivities(): Collection;
    
    /**
     * 查找即将开始的活动
     */
    public function findUpcomingActivities(int $minutes = 60): Collection;
    
    /**
     * 查找已过期但未结束的活动
     */
    public function findExpiredActivities(): Collection;

    /**
     * 根据活动类型查询
     */
    public function byType(string $type): static;
    
    /**
     * 查询正在进行的活动
     */
    public function running(): static;
    
    /**
     * 查询即将开始的活动
     */
    public function upcoming(int $minutes = 60): static;
    
    /**
     * 查询用户可参与的活动
     */
    public function availableForUser($user): static;
    
    /**
     * 查询商品相关的活动
     */
    public function byProduct(int $productId): static;
    
    /**
     * 查询分类相关的活动
     */
    public function byCategory(int $categoryId): static;
}
