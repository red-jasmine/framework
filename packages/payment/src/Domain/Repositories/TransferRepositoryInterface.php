<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 支付转账仓库接口
 *
 * 提供支付转账实体的读写操作统一接口
 *
 * @method Transfer  find($id)
 */
interface TransferRepositoryInterface extends RepositoryInterface
{
    public function findByNo(string $no) : ?Transfer;


}
