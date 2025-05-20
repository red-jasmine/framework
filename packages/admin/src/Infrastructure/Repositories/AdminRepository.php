<?php

namespace RedJasmine\Admin\Infrastructure\Repositories;

use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Admin\Domain\Repositories\AdminRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class AdminRepository extends EloquentRepository implements AdminRepositoryInterface
{

    protected static string $eloquentModelClass = Admin::class;

    public function findByName(string $name) : ?Admin
    {
        return static::$eloquentModelClass::where('name', $name)->first();
    }


}
