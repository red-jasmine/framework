<?php

namespace RedJasmine\Wallet\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Repositories\WalletReadRepositoryInterface;

class WalletReadRepository extends QueryBuilderReadRepository implements WalletReadRepositoryInterface
{

    public static string $modelClass = Wallet::class;

    public function findByOwnerType(UserInterface $owner, string $type) : ?Wallet
    {
        return $this->query()
                    ->onlyOwner($owner)
                    ->where('type', $type)
                    ->first();
    }


}