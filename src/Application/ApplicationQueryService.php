<?php

namespace RedJasmine\Support\Application;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use Illuminate\Contracts\Pagination\Paginator;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Support\Infrastructure\ReadRepositories\ReadRepositoryInterface;

/**
 *
 * @property ReadRepositoryInterface    $readRepository
 * @property QueryBuilderReadRepository $repository
 */
abstract class ApplicationQueryService extends Service
{

    public function __construct()
    {
        $this->initReadRepository();
        parent::__construct();
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


    public function withQuery(Closure $queryCallback = null) : static
    {
        if ($queryCallback) {
            $this->queryCallbacks[] = $queryCallback;
        }
        return $this;
    }


    /**
     * @param PaginateQuery|null $query
     *
     * @return LengthAwarePaginator
     */
    public function paginate(?PaginateQuery $query = null) : LengthAwarePaginator
    {
        return $this->repository->setQueryCallbacks($this->queryCallbacks)->paginate($query);
    }

    public function simplePaginate(?PaginateQuery $query = null) : Paginator
    {
        return $this->repository->setQueryCallbacks($this->queryCallbacks)->simplePaginate($query);
    }

    public function find(int $id, ?FindQuery $query = null) : mixed
    {

        return $this->repository->setQueryCallbacks($this->queryCallbacks)->find($id, $query);
    }


}
