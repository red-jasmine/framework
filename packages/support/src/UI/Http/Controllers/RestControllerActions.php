<?php

namespace RedJasmine\Support\UI\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @property ApplicationService $service
 * @property JsonResource $resourceClass
 * @property string|class-string<Model> $modelClass
 * @property string $paginateQueryClass
 * @property string $findQueryClass
 * @property string $commandClass
 * @property string $createCommandClass
 * @property string $updateCommandClass
 * @property string $dataClass
 */
trait RestControllerActions
{
    protected function getOwnerKey() : string
    {
        return property_exists($this, 'ownerKey') ? $this->ownerKey : 'owner';
    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        if (method_exists($this, 'authorize')) {
            $this->authorize('viewAny', static::$modelClass);
        }
        $queryClass = static::$paginateQueryClass ?? PaginateQuery::class;
        $result     = $this->service->paginate($queryClass::from($request));
        return static::$resourceClass::collection($result->appends($request->query()));
    }

    public function store(Request $request) : JsonResource
    {
        if ($request instanceof FormRequest) {
            $request->validated();
        }
        if (method_exists($this, 'authorize')) {
            $this->authorize('create', static::$modelClass);
        }

        $request->offsetSet($this->getOwnerKey(), $this->getOwner());

        $dataClass = static::$createCommandClass ?? static::$dataClass;

        $command = $dataClass::from($request);

        $result = $this->service->create($command);
        return new static::$resourceClass($result);
    }

    public function show($id, Request $request) : JsonResource
    {

        $query = property_exists($this, 'findQueryClass') ? ($this->findQueryClass)::from($request) : FindQuery::from($request);
        $query->setKey($id);
        $model = $this->service->find($query);
        if (method_exists($this, 'authorize')) {
            $this->authorize('view', $model);
        }
        return new (static::$resourceClass)($model);
    }

    public function update($id, Request $request) : JsonResource
    {
        if ($request instanceof FormRequest) {
            $request->validated();
        }
        $model = $this->service->find(FindQuery::from(['id' => $id]));

        if (method_exists($this, 'authorize')) {
            $this->authorize('update', $model);
        }
        $request->offsetSet($this->getOwnerKey(), $this->getOwner());
        $dataClass = static::$updateCommandClass ?? static::$dataClass;
        $command   = $dataClass::from($request);
        $command->setKey($id);
        $result = $this->service->update($command);
        return new (static::$resourceClass)($result);

    }

    public function destroy($id)
    {
        $model = $this->service->find(FindQuery::from(['id' => $id]));
        if (method_exists($this, 'authorize')) {
            $this->authorize('delete', $model);
        }
        $command = new Data();
        $command->setKey($id);
        $this->service->delete($command);
        return static::success();
    }
}
