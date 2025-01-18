<?php

namespace RedJasmine\Support\UI\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @property string $resourceClass
 * @property string $modelClass
 * @property string $dataClass
 */
trait RestControllerActions
{


    public function index(Request $request) : AnonymousResourceCollection
    {
        if (method_exists($this, 'authorize')) {
            $this->authorize('viewAny', static::$modelClass);
        }

        $result = $this->queryService->paginate(PaginateQuery::from($request->query()));
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
        $request->offsetSet('owner', $this->getOwner());
        $command = static::$dataClass::from($request);
        $result  = $this->commandService->create($command);
        return new static::$resourceClass($result);
    }

    public function show($id, Request $request) : JsonResource
    {

        $model = $this->queryService->findById(FindQuery::from(['id' => $id]));

        if (method_exists($this, 'authorize')) {
            $this->authorize('view', $model);
        }
        return new static::$resourceClass($model);
    }

    public function update($id, Request $request) : JsonResource
    {
        if ($request instanceof FormRequest) {
            $request->validated();
        }
        $model = $this->queryService->findById(FindQuery::from(['id' => $id]));

        if (method_exists($this, 'authorize')) {
            $this->authorize('update', $model);
        }
        $request->offsetSet('owner', $this->getOwner());
        $command = static::$dataClass::from($request);
        $command->setKey($id);
        $result = $this->commandService->update($command);
        return new static::$resourceClass($result);

    }

    public function destroy($id)
    {
        $model = $this->queryService->findById(FindQuery::from(['id' => $id]));
        if (method_exists($this, 'authorize')) {
            $this->authorize('delete', $model);
        }
        $command = new Data();
        $command->setKey($id);
        $this->commandService->delete($command);
        return $this::success();
    }
}
