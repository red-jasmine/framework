<?php

namespace RedJasmine\Support\Application;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use RedJasmine\Support\Application\Handlers\FindQueryHandler;
use RedJasmine\Support\Application\Handlers\PaginateQueryHandler;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

/**
 *
 * @method LengthAwarePaginator paginate(?PaginateQuery $query = null)
 * @method mixed find(int $id, ?FindQuery $query = null)
 * @property QueryBuilderReadRepository $repository
 */
abstract class ApplicationQueryService extends Service
{

    public function __construct()
    {
        $this->initReadRepository();
        parent::__construct();
    }


    protected static $macros = [
        'paginate' => PaginateQueryHandler::class,
        'find'     => FindQueryHandler::class,
    ];

    public function getRepository() : QueryBuilderReadRepository
    {
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

    public function allowedIncludes() : array
    {
        return [];
    }

    public function allowedFields() : array
    {
        return [];
    }

    public function allowedSorts() : array
    {
        return [];
    }

    /**
     * @var array
     */
    protected array $queryCallbacks = [];

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


    public function simplePaginate(?PaginateQuery $query = null) : Paginator
    {
        return $this->repository->setQueryCallbacks($this->queryCallbacks)->simplePaginate($query);
    }


}
