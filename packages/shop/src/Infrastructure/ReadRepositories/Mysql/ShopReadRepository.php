<?php

namespace RedJasmine\Shop\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Shop\Domain\Models\Shop;
use RedJasmine\Shop\Domain\Repositories\ShopReadRepositoryInterface;
use RedJasmine\User\Infrastructure\ReadRepositories\Mysql\UserReadRepository;

class ShopReadRepository extends UserReadRepository implements ShopReadRepositoryInterface
{
    public static string $modelClass = Shop::class;

    public function findByName(string $name): ?Shop
    {
        return parent::findByName($name);
    }

    public function findByEmail(string $email): ?Shop
    {
        return parent::findByEmail($email);
    }

    public function findByPhone(string $phone): ?Shop
    {
        return parent::findByPhone($phone);
    }

    public function findByAccount(string $account): ?Shop
    {
        return parent::findByAccount($account);
    }

    public function findByConditions($credentials): ?Shop
    {
        return parent::findByConditions($credentials);
    }
} 