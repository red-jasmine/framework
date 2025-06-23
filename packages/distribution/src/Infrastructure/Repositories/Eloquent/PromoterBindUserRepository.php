<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories\Eloquent;

use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Distribution\Domain\Repositories\PromoterBindUserRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PromoterBindUserRepository extends EloquentRepository implements PromoterBindUserRepositoryInterface
{
    protected static string $eloquentModelClass = PromoterBindUser::class;
    
    /**
     * 查找用户与分销员的绑定关系
     */
    public function findBindRelation(int $promoterId, UserInterface $user): ?PromoterBindUser
    {
        /** @var PromoterBindUser $modelClass */
        $modelClass = static::$eloquentModelClass;
        return $modelClass::query()
            ->where('promoter_id', $promoterId)
            ->where('user_type', $user->getType())
            ->where('user_id', $user->getID())
            ->first();
    }
} 