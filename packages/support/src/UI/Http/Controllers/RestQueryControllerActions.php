<?php

namespace RedJasmine\Support\UI\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\OwnerInjectionInterface;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;
use RedJasmine\User\Domain\Data\UserData;

/**
 * @property ApplicationService $service
 * @property string|class-string<Model> $modelClass
 * @property string $paginateQueryClass
 * @property string $findQueryClass
 * @property  JsonResource $resourceClass
 */
trait RestQueryControllerActions
{

    protected function requestInjectionOwner(Request $request, string $queryClass)
    {
        if (in_array(OwnerInjectionInterface::class, class_implements($queryClass))) {


           // $request->offsetSet($queryClass::getOwnerKey(), $this->getOwner());
        }
    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        if (method_exists($this, 'authorize')) {
            $this->authorize('viewAny', static::$modelClass);
        }
        $queryClass = static::$paginateQueryClass ?? PaginateQuery::class;
        $this->requestInjectionOwner($request, $queryClass);

        $result = $this->service->paginate($queryClass::from($request));
        return static::$resourceClass::collection($result->appends($request->query()));
    }


    public function show($id, Request $request) : JsonResource
    {
        $model = $this->findOne($id, $request);
        return new (static::$resourceClass)($model);
    }

    protected function findOne($id, Request $request)
    {
        $queryClass = static::$findQueryClass ?? FindQuery::class;

        $this->requestInjectionOwner($request, $queryClass);

        $query = $queryClass::from($request);
        $query->setKey($id);
        $model = $this->service->find($query);
        if (method_exists($this, 'authorize')) {
            $this->authorize('view', $model);
        }
        return $model;
    }

}