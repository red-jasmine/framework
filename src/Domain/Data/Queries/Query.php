<?php

namespace RedJasmine\Support\Domain\Data\Queries;

use RedJasmine\Support\Data\Data;

class Query extends Data
{

    /**
     * 关联加载的字段
     * @var mixed
     */
    public string|array|null $include;

    /**
     * 查询的字段
     * @var mixed
     */
    public string|array|null $fields;


}
