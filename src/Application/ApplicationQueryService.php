<?php

namespace RedJasmine\Support\Application;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Infrastructure\ReadRepositories\ReadRepositoryInterface;

/**
 * @property ReadRepositoryInterface $readRepository
 */
class ApplicationQueryService
{

    public function __construct()
    {
        $this->initReadRepository();
    }

    protected function initReadRepository() : void
    {
        $this->readRepository->setAllowedFilters($this->allowedFilters);
        $this->readRepository->setAllowedFields($this->allowedFields);
        $this->readRepository->setAllowedIncludes($this->allowedIncludes);
        $this->readRepository->setAllowedSorts($this->allowedSorts);
    }

    // 每个查询
    protected array $allowedFilters  = [];
    protected array $allowedIncludes = [];
    protected array $allowedFields   = [];
    protected array $allowedSorts    = [];

    /**
     * @var array
     */
    protected array $queryCallbacks = [];


    public function withQuery(Closure $queryCallback = null) : static
    {
        $this->queryCallbacks[] = $queryCallback;
        return $this;
    }


    /**
     * @param array $query
     *
     * @return LengthAwarePaginator
     */
    public function paginate(array $query = []) : LengthAwarePaginator
    {
        return $this->readRepository->setQueryCallbacks($this->queryCallbacks)->findAll($query);
    }

    public function find(int $id, array $query = []) : mixed
    {
        return $this->readRepository->setQueryCallbacks($this->queryCallbacks)->findById($id, $query);
    }


}
