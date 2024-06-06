<?php

namespace RedJasmine\Support\Application\Handlers;

use RedJasmine\Support\Application\QueryHandler;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;

class FindQueryHandler extends QueryHandler
{

    public function handle(int $id, ?FindQuery $query = null) : mixed
    {

        return $this->getService()->getRepository()->setQueryCallbacks($this->getService()->getQueryCallbacks())->find($id, $query);

    }

}
