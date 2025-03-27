<?php

namespace RedJasmine\Vip\Infrastructure\ReadRepositories;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Vip\Domain\Models\VipProduct;
use RedJasmine\Vip\Domain\Repositories\VipProductReadRepositoryInterface;
use RedJasmine\Vip\Infrastructure\ProductDomainConverter;


class VipProductReadRepository implements VipProductReadRepositoryInterface
{


    public function __construct(
        public ProductApplicationService $queryService,
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
        return $this->queryService->readRepository->query($query);
    }

    public function withQuery(Closure $queryCallback) : static
    {
        $this->queryService->readRepository->withQuery($queryCallback);
        return $this;
    }


    public function find(FindQuery $query) : ?Model
    {
        $product = $this->queryService->readRepository->find($query);

        return $this->productDomainConverter->converter($product);
    }


    public function paginate(PaginateQuery $query) : LengthAwarePaginator|Paginator
    {

        //dd($query);
        $query->additional(['product_model' => $query->type]);
        unset($query->type);

        $lengthAwarePaginator = $this->queryService->readRepository->paginate($query);

        return $lengthAwarePaginator->setCollection(
            $lengthAwarePaginator->getCollection()->map(fn($item
            ) => $this->productDomainConverter->converter($item)));

    }


}