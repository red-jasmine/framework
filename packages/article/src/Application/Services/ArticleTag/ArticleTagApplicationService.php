<?php

namespace RedJasmine\Article\Application\Services\ArticleTag;

use RedJasmine\Article\Domain\Models\ArticleTag;
use RedJasmine\Article\Domain\Repositories\ArticleTagRepositoryInterface;
use RedJasmine\Article\Domain\Transformer\ArticleTagTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 文章标签应用服务
 *
 * 使用统一的仓库接口，支持读写操作
 */
class ArticleTagApplicationService extends ApplicationService
{
    public function __construct(
        public ArticleTagRepositoryInterface $repository,
        public ArticleTagTransformer $transformer,
    ) {
    }

    protected static string $modelClass = ArticleTag::class;
}
