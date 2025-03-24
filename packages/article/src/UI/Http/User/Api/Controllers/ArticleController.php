<?php

namespace RedJasmine\Article\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Article\Application\Services\Article\ArticleApplicationService;
use RedJasmine\Article\UI\Http\User\Api\Resources\ArticleResource;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ArticleController extends Controller
{


    protected $resourceClass = ArticleResource::class;

    public function __construct(
        protected ArticleApplicationService $service
    ) {

    }

    public function index(Request $request)
    {
        $result = $this->service->paginate(PaginateQuery::from($request));

    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
