<?php

namespace RedJasmine\Promotion\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

/**
 * 活动只读仓库接口
 */
interface ActivityReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 根据活动类型查询
     * 
     * @param string $type
     * @return static
     */
    public function byType(string $type): static;
    
    /**
     * 查询正在进行的活动
     * 
     * @return static
     */
    public function running(): static;
    
    /**
     * 查询即将开始的活动
     * 
     * @param int $minutes
     * @return static
     */
    public function upcoming(int $minutes = 60): static;
    
    /**
     * 查询用户可参与的活动
     * 
     * @param \RedJasmine\Support\Contracts\UserInterface $user
     * @return static
     */
    public function availableForUser($user): static;
    
    /**
     * 查询商品相关的活动
     * 
     * @param int $productId
     * @return static
     */
    public function byProduct(int $productId): static;
    
    /**
     * 查询分类相关的活动
     * 
     * @param int $categoryId
     * @return static
     */
    public function byCategory(int $categoryId): static;
}
