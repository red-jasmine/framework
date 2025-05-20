<?php

namespace RedJasmine\Admin\Domain\Repositories;

use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method Admin  find($id)
 */
interface AdminRepositoryInterface extends RepositoryInterface
{

    public function findByName(string $name) : ?Admin;

}
