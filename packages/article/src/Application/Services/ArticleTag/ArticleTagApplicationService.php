<?php

namespace RedJasmine\Article\Application\Services\ArticleTag;

use RedJasmine\Article\Domain\Models\ArticleTag;
use RedJasmine\Article\Domain\Repositories\ArticleTagReadRepositoryInterface;
use RedJasmine\Article\Domain\Repositories\ArticleTagRepositoryInterface;
use RedJasmine\Article\Domain\Transformer\ArticleTagTransformer;
use RedJasmine\Support\Application\ApplicationService;

class ArticleTagApplicationService extends ApplicationService
{
    public function __construct(
        public ArticleTagRepositoryInterface $repository,
        public ArticleTagReadRepositoryInterface $readRepository,
        public ArticleTagTransformer $transformer,
    ) {
    }

    protected static string $modelClass = ArticleTag::class;
}