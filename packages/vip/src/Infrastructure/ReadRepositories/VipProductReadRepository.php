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

class VipProductReadRepository implements VipProductReadRepositoryInterface
{

    public function __construct(
        public ProductQueryService $queryService,
    ) {

        $this->queryService->getRepository()->withQuery(function ($query) {
            return $query->where('app_id', 'vip');
        });
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

        return $this->converter($product);
    }

    public function paginate(?PaginateQuery $query = null) : LengthAwarePaginator
    {


        $lengthAwarePaginator = $this->queryService->getRepository()->paginate($query);

        return $lengthAwarePaginator->setCollection(
            $lengthAwarePaginator->getCollection()->map(fn($item
            ) => $this->converter($item)));

    }

    public function simplePaginate(?PaginateQuery $query = null) : Paginator
    {
        $lengthAwarePaginator = $this->queryService->getRepository()->simplePaginate($query);

        return $lengthAwarePaginator->setCollection(
            $lengthAwarePaginator->getCollection()->map(fn($item
            ) => $this->converter($item)));
    }

    protected function converter(Product $product) : VipProduct
    {
        /**
         * @var VipProduct $model
         */
        $model = VipProduct::make();

        $model->id         = $product->id;
        $model->price      = $product->price;
        $model->name       = $product->title;
        $model->stock      = $product->stock;
        $model->status     = VipProductStatusEnum::from($product->status->value);
        $model->time_value = $product->unit_quantity;
        $model->app_id     = $product->extension->extras['app_id'];
        $model->type       = $product->extension->extras['type'];
        $model->time_unit  = TimeUnitEnum::from($product->unit);
        return $model;
    }

}