<?php

namespace RedJasmine\Article\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Article\Application\Services\Article\ArticleApplicationService;
use RedJasmine\Article\Application\Services\Article\Queries\PaginateQuery;
use RedJasmine\Article\UI\Http\User\Api\Resources\ArticleResource;

class ArticleController extends Controller
{


    protected static string $resourceClass = ArticleResource::class;

    protected static string $paginateQueryClass = PaginateQuery::class;

    public function __construct(
        protected ArticleApplicationService $service,
    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });

    }

    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->service->paginate(static::$paginateQueryClass::from($request));
        return static::$resourceClass::collection($result);
    }


    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }


    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
