<?php

namespace RedJasmine\Support\Application;

use Closure;
use RedJasmine\Support\Application\QueryHandlers\FindQueryHandler;
use RedJasmine\Support\Application\QueryHandlers\PaginateQueryHandler;
use RedJasmine\Support\Application\QueryHandlers\SimplePaginateQueryHandler;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;


abstract class ApplicationQueryService extends Service
{

    protected static $macros = [
        'paginate'       => PaginateQueryHandler::class,
        'find'           => FindQueryHandler::class,
        'simplePaginate' => SimplePaginateQueryHandler::class,
    ];
    /**
     * @var array
     */
    protected array $queryCallbacks = [];

    public function __construct()
    {
        $this->initReadRepository();

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

    public function getRepository() : QueryBuilderReadRepository
    {
        return $this->repository;
    }

    public function getQueryCallbacks() : array
    {
        return $this->queryCallbacks;
    }


    public function withQuery(Closure $queryCallback = null) : static
    {
        if ($queryCallback) {
            $this->queryCallbacks[] = $queryCallback;
        }
        return $this;
    }


    public function getModelQuery() : \Illuminate\Database\Eloquent\Builder
    {
        return $this->repository->getModelQuery();
    }

}
