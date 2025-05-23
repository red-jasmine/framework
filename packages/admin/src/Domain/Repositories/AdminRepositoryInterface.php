<?php

namespace RedJasmine\Admin\Domain\Repositories;

use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;

/**
 * @method Admin  find($id)
 */
interface AdminRepositoryInterface extends UserRepositoryInterface
{

    public function findByName(string $name) : ?Admin;

}
