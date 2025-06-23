<?php

namespace RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Distribution\Domain\Repositories\PromoterBindUserReadRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class PromoterBindUserReadRepository extends QueryBuilderReadRepository implements PromoterBindUserReadRepositoryInterface
{
    protected static string $modelClass = PromoterBindUser::class;

    public function allowedIncludes(): array
    {
        return [
            'promoter'
        ];
    }

    public function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('promoter_id'),
            AllowedFilter::exact('user_type'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::callback('search', static function (Builder $builder, $value) {
                return $builder->where(function (Builder $builder) use ($value) {
                    $builder->where('user_id', 'like', '%'.$value.'%');
                });
            })
        ];
    }

    /**
     * 查找用户与分销员的绑定关系
     */
    public function findBindRelation(int $promoterId, UserInterface $user): ?PromoterBindUser
    {
        return $this->query()
            ->where('promoter_id', $promoterId)
            ->where('user_type', $user->getType())
            ->where('user_id', $user->getID())
            ->first();
    }
    
    /**
     * 查找用户与分销员的有效绑定关系
     */
    public function findActiveBind(int $promoterId, UserInterface $user): ?PromoterBindUser
    {
        return $this->query()
            ->where('promoter_id', $promoterId)
            ->where('user_type', $user->getType())
            ->where('user_id', $user->getID())
            ->where('status', PromoterBindUserStatusEnum::BOUND)
            ->where('expires_at', '>', now())
            ->first();
    }
    
    /**
     * 查找用户的当前有效绑定关系（不指定分销员）
     */
    public function findUserActiveBind(UserInterface $user): ?PromoterBindUser
    {
        return $this->query()
            ->where('user_type', $user->getType())
            ->where('user_id', $user->getID())
            ->where('status', PromoterBindUserStatusEnum::BOUND)
            ->where('expires_at', '>', now())
            ->first();
    }
} 