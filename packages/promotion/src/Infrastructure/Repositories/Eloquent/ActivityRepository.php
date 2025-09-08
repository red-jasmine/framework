<?php

namespace RedJasmine\Promotion\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum;
use RedJasmine\Promotion\Domain\Repositories\ActivityRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

/**
 * 活动仓库实现
 */
class ActivityRepository extends Repository implements ActivityRepositoryInterface
{
    protected static string $modelClass = Activity::class;
    
    public function find($id): ?Activity
    {
        return Activity::find($id);
    }
    
    public function store(\Illuminate\Database\Eloquent\Model $model): \Illuminate\Database\Eloquent\Model
    {
        $model->save();
        return $model;
    }
    
    public function update(\Illuminate\Database\Eloquent\Model $model): void
    {
        $model->save();
    }
    
    public function delete(\Illuminate\Database\Eloquent\Model $model): bool
    {
        return $model->delete();
    }
    
    public function findByType(string $type): Collection
    {
        return Activity::where('type', $type)->get();
    }
    
    public function findRunningActivities(): Collection
    {
        return Activity::where('status', ActivityStatusEnum::RUNNING)
            ->where('start_time', '<=', now())
            ->where('end_time', '>', now())
            ->get();
    }
    
    public function findUpcomingActivities(int $minutes = 60): Collection
    {
        return Activity::where('status', ActivityStatusEnum::PENDING)
            ->where('start_time', '>', now())
            ->where('start_time', '<=', now()->addMinutes($minutes))
            ->get();
    }
    
    public function findExpiredActivities(): Collection
    {
        return Activity::where('status', ActivityStatusEnum::RUNNING)
            ->where('end_time', '<', now())
            ->get();
    }
}
