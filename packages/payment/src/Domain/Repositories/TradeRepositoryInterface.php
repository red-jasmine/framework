<?php

namespace RedJasmine\Payment\Domain\Repositories;

use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 支付交易仓库接口
 *
 * 提供支付交易实体的读写操作统一接口
 *
 * @method Trade  find($id)
 */
interface TradeRepositoryInterface extends RepositoryInterface
{
    public function findByNo(string $no) : ?Trade;


}
