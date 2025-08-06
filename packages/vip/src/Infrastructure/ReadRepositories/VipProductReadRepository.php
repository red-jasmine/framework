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
        public ProductApplicationService $productApplicationService,
        public ProductDomainConverter $productDomainConverter
    ) {

        // $this->queryService->getRepository()->withQuery(function ($query) {
        //     return $query->where('biz', 'vip');
        // });
    }

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = VipProduct::class;

    public function modelQuery(?Query $query = null) : Builder
    {
        return $this->productApplicationService->readRepository->modelQuery();
    }

    public function query(?Query $query = null)
    {
        return $this->productApplicationService->readRepository->query($query);
    }

    public function withQuery(Closure $queryCallback) : static
    {
        $this->productApplicationService->readRepository->withQuery($queryCallback);
        return $this;
    }


    public function find(FindQuery $query) : ?Model
    {
        $product = $this->productApplicationService->readRepository->find($query);

        return $this->productDomainConverter->converter($product);
    }


    public function paginate(PaginateQuery $query) : LengthAwarePaginator|Paginator
    {

        //dd($query);
        $query->additional(['product_model' => $query->type]);
        unset($query->type);

        $lengthAwarePaginator = $this->productApplicationService->readRepository->paginate($query);

        return $lengthAwarePaginator->setCollection(
            $lengthAwarePaginator->getCollection()->map(fn($item
            ) => $this->productDomainConverter->converter($item)));

    }


}