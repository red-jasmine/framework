<?php

namespace RedJasmine\Article\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Article\Application\Services\Article\ArticleApplicationService;
use RedJasmine\Article\UI\Http\User\Api\Resources\ArticleResource;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ArticleController extends Controller
{


    protected static string $resourceClass = ArticleResource::class;

    public function __construct(
        protected ArticleApplicationService $service
    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });

    }

    public function index(Request $request)
    {
        $result = $this->service->paginate(PaginateQuery::from($request));
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
