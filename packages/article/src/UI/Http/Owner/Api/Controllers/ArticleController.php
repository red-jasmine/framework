<?php

namespace RedJasmine\Article\UI\Http\Owner\Api\Controllers;

use RedJasmine\Article\Application\Services\Article\ArticleApplicationService;
use RedJasmine\Article\Domain\Data\ArticleData;
use RedJasmine\Article\Domain\Data\Queries\ArticleListQuery;
use RedJasmine\Article\UI\Http\Owner\Api\Resources\ArticleResource;
use RedJasmine\Support\UI\Http\Controllers\Controller;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;


/**
 * 文章 Owner 端控制器
 */
class ArticleController extends Controller
{
    use RestControllerActions;


    protected static string $resourceClass = ArticleResource::class;
    protected static string $paginateQueryClass = ArticleListQuery::class;
    protected static string $modelClass = \RedJasmine\Article\Domain\Models\Article::class;
    protected static string $dataClass = ArticleData::class;

    public function __construct(
        protected ArticleApplicationService $service,
    ) {
        // 设置查询作用域，只查询当前 Owner 的数据
        $this->service->repository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    /**
     * 发布文章
     */
    public function publish($id)
    {
        $article = $this->service->find($id);

        if (!$article) {
            return new JsonResponse(['message' => '文章不存在'], 404);
        }

        $this->service->publish($article);

        return new JsonResponse([
            'message' => '文章发布成功',
            'data' => new ArticleResource($article->fresh())
        ]);
    }

    /**
     * 取消发布文章
     */
    public function unpublish($id)
    {
        $article = $this->service->find($id);

        if (!$article) {
            return new JsonResponse(['message' => '文章不存在'], 404);
        }

        $article->status = \RedJasmine\Article\Domain\Models\Enums\ArticleStatusEnum::DRAFT;
        $article->save();

        return new JsonResponse([
            'message' => '文章已取消发布',
            'data' => new ArticleResource($article->fresh())
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
