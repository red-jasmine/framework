<?php

namespace RedJasmine\Wallet\Domain\Repositories;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Wallet\Domain\Models\Wallet;

interface WalletReadRepositoryInterface extends ReadRepositoryInterface
{

    public function findByOwnerType(UserInterface $owner, string $type) : ?Wallet;

}