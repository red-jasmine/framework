<?php

namespace RedJasmine\Warehouse\Exceptions;

use RedJasmine\Support\Exceptions\BaseException;

class WarehouseException extends BaseException
{
    public const WAREHOUSE_NOT_FOUND = 301001; // 仓库不存在
    public const WAREHOUSE_CODE_EXISTS = 301002; // 仓库编码已存在
    public const WAREHOUSE_NOT_ACTIVE = 301003; // 仓库未启用
    public const DEFAULT_WAREHOUSE_NOT_FOUND = 301004; // 默认仓库不存在
    public const WAREHOUSE_MARKET_EXISTS = 301005; // 仓库市场关联已存在
}

