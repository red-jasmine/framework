<?php

namespace RedJasmine\User\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\User\Domain\Models\User;

interface UserRepositoryInterface extends RepositoryInterface
{

    public function findByName(string $name) : ?User;

}
