<?php

namespace RedJasmine\Project\Application\Services\Queries;

use RedJasmine\Support\Application\Queries\FindQuery;

class ProjectFindQuery extends FindQuery
{
    public mixed $id = null;
    public ?string $code = null;
    public ?string $name = null;
}
