<?php

namespace RedJasmine\Wallet\Domain\Repositories;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Wallet\Domain\Models\Wallet;

interface WalletRepositoryInterface extends RepositoryInterface
{
    public function findLock($id) : Wallet;

    public function findByOwnerType(UserInterface $owner, string $type) : ?Wallet;
}