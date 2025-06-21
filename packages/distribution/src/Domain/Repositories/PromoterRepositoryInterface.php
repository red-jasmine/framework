<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface PromoterRepositoryInterface extends RepositoryInterface
{


    public function findByOwner(UserInterface $owner) : ?Promoter;
}