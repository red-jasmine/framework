<?php

namespace RedJasmine\Promotion\Domain\Repositories;

use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 活动仓库接口
 */
interface ActivityRepositoryInterface extends RepositoryInterface
{
    /**
     * 查找活动
     * 
     * @param mixed $id
     * @return Activity|null
     */
    public function find($id): ?Activity;
    
    /**
     * 存储活动
     * 
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(\Illuminate\Database\Eloquent\Model $model): \Illuminate\Database\Eloquent\Model;
    
    /**
     * 更新活动
     * 
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function update(\Illuminate\Database\Eloquent\Model $model): void;
    
    /**
     * 删除活动
     * 
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function delete(\Illuminate\Database\Eloquent\Model $model): bool;
    
    /**
     * 根据类型查找活动
     * 
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByType(string $type);
    
    /**
     * 查找正在进行的活动
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findRunningActivities();
    
    /**
     * 查找即将开始的活动
     * 
     * @param int $minutes 多少分钟内开始
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findUpcomingActivities(int $minutes = 60);
    
    /**
     * 查找已过期但未结束的活动
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findExpiredActivities();
}
