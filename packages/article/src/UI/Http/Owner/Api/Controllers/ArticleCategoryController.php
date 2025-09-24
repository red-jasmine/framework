<?php

namespace RedJasmine\Article\UI\Http\Owner\Api\Controllers;

use RedJasmine\Article\Application\Services\ArticleCategory\ArticleCategoryApplicationService;
use RedJasmine\Article\Domain\Data\ArticleCategoryData;
use RedJasmine\Article\Domain\Data\Queries\ArticleCategoryListQuery;
use RedJasmine\Article\UI\Http\Owner\Api\Resources\ArticleCategoryResource;
use RedJasmine\Article\UI\Http\Owner\Api\Requests\ArticleCategoryCreateRequest;
use RedJasmine\Article\UI\Http\Owner\Api\Requests\ArticleCategoryUpdateRequest;
use RedJasmine\Support\Http\Controllers\Controller;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;
use RedJasmine\Support\UI\Http\Controllers\UserOwnerTools;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


/**
 * 文章分类 Owner 端控制器
 */
class ArticleCategoryController extends Controller
{
    use RestControllerActions;


    protected static string $resourceClass = ArticleCategoryResource::class;
    protected static string $paginateQueryClass = ArticleCategoryListQuery::class;
    protected static string $modelClass = \RedJasmine\Article\Domain\Models\ArticleCategory::class;
    protected static string $dataClass = ArticleCategoryData::class;

    public function __construct(
        protected ArticleCategoryApplicationService $service,
    ) {
        // 设置查询作用域，只查询当前 Owner 的数据
        $this->service->repository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    /**
     * 获取树形结构
     */
    public function tree(Request $request)
    {
        $query = new ArticleCategoryListQuery($request->all());
        $tree = $this->service->tree($query);

        return new JsonResponse([
            'data' => $tree
        ]);
    }

    /**
     * 权限验证
     */
    public function authorize($ability, $arguments = []): bool
    {
        // Owner 端权限验证逻辑
        return true;
    }
}
