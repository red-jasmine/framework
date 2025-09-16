<?php

namespace RedJasmine\Address\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 地址仓库接口
 *
 * 继承RepositoryInterface，提供地址实体的读写操作统一接口
 */
interface AddressRepositoryInterface extends RepositoryInterface
{
    // 如需特定的地址业务方法，可在此扩展
}
