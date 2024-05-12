<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

use RedJasmine\Support\Data\Data;

class FindQuery extends Data
{
    public string|array|null $includes;

    public string|array|null $fields;

    public string|array|null $append;


}
