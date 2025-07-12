<?php

namespace RedJasmine\Support\Domain\Data\Queries;

use RedJasmine\Support\Data\Data;

class Query extends Data
{

    /**
     * 关联加载的字段
     * @var mixed
     */
    public mixed $include = [];

    /**
     * 查询的字段
     * @var mixed
     */
    public mixed $fields = [];

    public mixed $append = [];

    public mixed $sort = [];


}
