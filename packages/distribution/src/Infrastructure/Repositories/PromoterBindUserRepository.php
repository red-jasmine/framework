<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterBindUserStatusEnum;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Distribution\Domain\Repositories\PromoterBindUserRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 推广员绑定用户仓库实现
 *
 * 基于Repository实现，提供推广员绑定用户实体的读写操作能力
 */
class PromoterBindUserRepository extends Repository implements PromoterBindUserRepositoryInterface
{
    protected static string $modelClass = PromoterBindUser::class;

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            'promoter'
        ];
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
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
        /** @var PromoterBindUser $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::query()
                          ->where('promoter_id', $promoterId)
                          ->where('user_type', $user->getType())
                          ->where('user_id', $user->getID())
                          ->first();
    }

    public function findUser(UserInterface $user): ?PromoterBindUser
    {
        /** @var PromoterBindUser $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::query()
                          ->where('user_type', $user->getType())
                          ->where('user_id', $user->getID())
                          ->first();
    }

    /**
     * 查询邀请中的状态
     */
    public function findUserInviting(UserInterface $user): ?PromoterBindUser
    {
        /** @var PromoterBindUser $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::query()
                          ->where('user_type', $user->getType())
                          ->where('user_id', $user->getID())
                          ->where('status', PromoterBindUserStatusEnum::INVITING)
                          ->first();
    }

    /**
     * 获取用户 生效的 绑定关系
     */
    public function findUserBound(UserInterface $user): ?PromoterBindUser
    {
        /** @var PromoterBindUser $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::query()
                          ->where('user_type', $user->getType())
                          ->where('user_id', $user->getID())
                          ->bound()
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
                    ->where('expiration_time', '>', now())
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
                    ->where('expiration_time', '>', now())
                    ->first();
    }
} 