<?php

namespace RedJasmine\User\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\User\Domain\Models\User;

interface UserReadRepositoryInterface extends ReadRepositoryInterface
{
    public function findByName(string $name) : ?User;
}
