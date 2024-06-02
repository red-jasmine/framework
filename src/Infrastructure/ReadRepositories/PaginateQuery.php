<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

use RedJasmine\Support\Data\Data;

class PaginateQuery extends Data
{

    public ?int $page = null;

    public ?int $perPage = null;

    public mixed $include;

    public mixed $fields;

    public mixed $append;

    public mixed $sort;

}
