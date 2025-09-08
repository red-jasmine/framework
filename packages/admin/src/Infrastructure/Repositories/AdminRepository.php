<?php

namespace RedJasmine\Admin\Infrastructure\Repositories;

use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Admin\Domain\Repositories\AdminRepositoryInterface;
use RedJasmine\User\Infrastructure\Repositories\UserRepository;

class AdminRepository extends UserRepository implements AdminRepositoryInterface
{

    protected static string $modelClass = Admin::class;

    public function findByName(string $name) : ?Admin
    {
        return parent::findByName($name); 
    }


}
