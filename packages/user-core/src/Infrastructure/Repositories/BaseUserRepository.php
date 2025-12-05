<?php

namespace RedJasmine\UserCore\Infrastructure\Repositories;

use RedJasmine\Support\Foundation\Facades\AES;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\UserCore\Domain\Models\User;
use RedJasmine\UserCore\Domain\Repositories\BaseUserRepositoryInterface;

abstract class BaseUserRepository extends Repository implements BaseUserRepositoryInterface
{

    public function findByName(string $name) : ?User
    {
        return $this->query()->where('name', $name)->first();
    }

    public function findByEmail(string $email) : ?User
    {
        return $this->query()->where('email', AES::encryptString($email))->first();
    }

    public function findByPhone(string $phone) : ?User
    {
        return $this->query()->where('phone', AES::encryptString($phone))->first();
    }

    public function findByAccount(string $account) : ?User
    {
        return $this->query()
                    ->where('name', $account)
                    ->orWhere('email', AES::encryptString($account))
                    ->orWhere('phone', AES::encryptString($account))
                    ->first();
    }

    public function findByConditions($credentials) : ?User
    {
        return $this->query()->where($credentials)->first();
    }
}