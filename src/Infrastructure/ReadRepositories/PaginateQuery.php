<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

use RedJasmine\Support\Data\Data;

class PaginateQuery extends Data
{

    public ?int $page = null;

    public ?int $perPage = null;

    public string|array|null $include;

    public string|array|null $fields;

    public string|array|null $append;

    public string|array|null $sort;

}
