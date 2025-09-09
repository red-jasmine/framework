<?php

namespace RedJasmine\Article\Application\Services\ArticleCategory;

use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\Article\Domain\Repositories\ArticleCategoryRepositoryInterface;
use RedJasmine\Article\Domain\Transformer\ArticleCategoryTransformer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;

/**
 * 文章分类应用服务
 *
 * 使用统一的仓库接口，支持读写操作
 */
class ArticleCategoryApplicationService extends ApplicationService
{
    public function __construct(
        public ArticleCategoryRepositoryInterface $repository,
        public ArticleCategoryTransformer $transformer
    ) {
    }

    protected static string $modelClass = ArticleCategory::class;

    /**
     * 获取树形结构
     */
    public function tree(Query $query) : array
    {
        return $this->repository->tree($query);
    }
}
