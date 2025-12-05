<?php

namespace RedJasmine\Article\UI\Http\Owner\Api\Controllers;

use RedJasmine\Article\Application\Services\ArticleTag\ArticleTagApplicationService;
use RedJasmine\Article\Domain\Data\ArticleTagData;
use RedJasmine\Article\Domain\Data\Queries\ArticleTagListQuery;
use RedJasmine\Article\UI\Http\Owner\Api\Resources\ArticleTagResource;
use RedJasmine\Support\UI\Http\Controllers\Controller;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

/**
 * 文章标签 Owner 端控制器
 */
class ArticleTagController extends Controller
{
    use RestControllerActions;


    protected static string $resourceClass = ArticleTagResource::class;
    protected static string $paginateQueryClass = ArticleTagListQuery::class;
    protected static string $modelClass = \RedJasmine\Article\Domain\Models\ArticleTag::class;
    protected static string $dataClass = ArticleTagData::class;

    public function __construct(
        protected ArticleTagApplicationService $service,
    ) {
        // 设置查询作用域，只查询当前 Owner 的数据
        $this->service->repository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
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
