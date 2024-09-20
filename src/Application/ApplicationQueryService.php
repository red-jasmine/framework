<?php

namespace RedJasmine\Support\Application;

use RedJasmine\Support\Application\QueryHandlers\FindQueryHandler;
use RedJasmine\Support\Application\QueryHandlers\PaginateQueryHandler;
use RedJasmine\Support\Application\QueryHandlers\SimplePaginateQueryHandler;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Foundation\Service\Service;


abstract class ApplicationQueryService extends Service
{


    protected static $macros = [
        'paginate'       => PaginateQueryHandler::class,
        'find'           => FindQueryHandler::class,
        'simplePaginate' => SimplePaginateQueryHandler::class,
    ];




    /**
     * @return ReadRepositoryInterface
     */
    public function getRepository() : ReadRepositoryInterface
    {

        $this->initReadRepository();

        return $this->repository;
    }

    protected function initReadRepository() : void
    {
        $this->repository->setAllowedFilters($this->allowedFilters());
        $this->repository->setAllowedFields($this->allowedFields());
        $this->repository->setAllowedIncludes($this->allowedIncludes());
        $this->repository->setAllowedSorts($this->allowedSorts());
    }

    public function allowedFilters() : array
    {
        return [];
    }

    public function allowedFields() : array
    {
        return [];
    }

    public function allowedIncludes() : array
    {
        return [];
    }

    public function allowedSorts() : array
    {
        return [];
    }


}
