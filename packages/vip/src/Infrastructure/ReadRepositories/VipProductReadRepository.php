<?php

namespace RedJasmine\Vip\Infrastructure\ReadRepositories;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Product\Application\Product\Services\ProductQueryService;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Domain\Data\Enums\TimeUnitEnum;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Vip\Domain\Models\Enums\VipProductStatusEnum;
use RedJasmine\Vip\Domain\Models\VipProduct;
use RedJasmine\Vip\Domain\Repositories\VipProductReadRepositoryInterface;
use RedJasmine\Vip\Infrastructure\ProductDomainConverter;

class VipProductReadRepository implements VipProductReadRepositoryInterface
{

    public function __construct(
        public ProductQueryService $queryService,
        public ProductDomainConverter $productDomainConverter
    ) {

        // $this->queryService->getRepository()->withQuery(function ($query) {
        //     return $query->where('app_id', 'vip');
        // });
    }

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = VipProduct::class;

    public function modelQuery() : Builder
    {
        return $this->queryService->getRepository()->modelQuery();
    }

    public function query(?Query $query = null)
    {
        return $this->queryService->getRepository()->query($query);
    }

    public function withQuery(Closure $queryCallback) : static
    {
        $this->queryService->getRepository()->withQuery($queryCallback);
        return $this;
    }

    public function findById(FindQuery $query) : ?Model
    {
        $product = $this->queryService->getRepository()->findById($query);

        return $this->productDomainConverter->converter($product);
    }

    public function paginate(?PaginateQuery $query = null) : LengthAwarePaginator
    {

        //dd($query);
        $query->additional(['product_model'=>$query->type]);
        unset($query->type);

        $lengthAwarePaginator = $this->queryService->getRepository()->paginate($query);

        return $lengthAwarePaginator->setCollection(
            $lengthAwarePaginator->getCollection()->map(fn($item
            ) => $this->productDomainConverter->converter($item)));

    }

    public function simplePaginate(?PaginateQuery $query = null) : Paginator
    {
        $lengthAwarePaginator = $this->queryService->getRepository()->simplePaginate($query);

        return $lengthAwarePaginator->setCollection(
            $lengthAwarePaginator->getCollection()->map(fn($item
            ) => $this->converter($item)));
    }



}