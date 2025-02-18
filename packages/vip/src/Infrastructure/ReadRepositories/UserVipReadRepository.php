<?php

namespace RedJasmine\Vip\Infrastructure\ReadRepositories;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Repositories\UserVipReadRepositoryInterface;

class UserVipReadRepository extends QueryBuilderReadRepository implements UserVipReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = UserVip::class;

    public function findVipByOwner(UserInterface $owner, string $appID, string $type) : ?UserVip
    {
        return $this->query()
                    ->where('owner_type', $owner->getType())
                    ->where('owner_id', $owner->getID())
                    ->where('app_id', $appID)
                    ->where('type', $type)
                    ->first();
    }


}