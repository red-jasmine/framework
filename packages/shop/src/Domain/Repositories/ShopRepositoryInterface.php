<?php

namespace RedJasmine\Shop\Domain\Repositories;

use RedJasmine\Shop\Domain\Models\Shop;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;

/**
 * @method Shop find($id)
 */
interface ShopRepositoryInterface extends UserRepositoryInterface
{
    public function findByName(string $name): ?Shop;
} 