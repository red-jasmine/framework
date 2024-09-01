<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

class FindQueryHandler extends QueryHandler
{
    public function __construct(
        protected  ReadRepositoryInterface $readRepository
    )
    {
    }


    public function handle(Data $data)
    {
        $this->readRepository->find($data->id,);

    }
}
