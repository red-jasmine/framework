<?php

namespace RedJasmine\Wallet\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface WalletRechargeRepositoryInterface extends RepositoryInterface
{

    public function findByNo(string $no);

    public function findByNoLock(string $no);

}