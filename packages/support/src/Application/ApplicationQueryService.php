<?php

namespace RedJasmine\Support\Application;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Application\QueryHandlers\FindQueryHandler;
use RedJasmine\Support\Application\QueryHandlers\PaginateQueryHandler;
use RedJasmine\Support\Application\QueryHandlers\SimplePaginateQueryHandler;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;


/**
 *
 * @method Model findById(FindQuery $query)
 * @method LengthAwarePaginator paginate(PaginateQuery $query)
 * @method Paginator simplePaginate(PaginateQuery $query)
 * @property ReadRepositoryInterface $repository
 */
abstract class ApplicationQueryService extends Service
{


    protected static $macros = [
        'findById'       => FindQueryHandler::class,
        'paginate'       => PaginateQueryHandler::class,
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
        if( $this->repository instanceof  QueryBuilderReadRepository){
            $this->repository->setAllowedFilters($this->allowedFilters());
            $this->repository->setAllowedFields($this->allowedFields());
            $this->repository->setAllowedIncludes($this->allowedIncludes());
            $this->repository->setAllowedSorts($this->allowedSorts());
        }

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
