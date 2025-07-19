<?php

namespace RedJasmine\Wallet\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;

interface WalletWithdrawalRepositoryInterface extends RepositoryInterface
{

    public function findByNo(string $no) : WalletWithdrawal;

    public function findByNoLock(string $no) : WalletWithdrawal;

}