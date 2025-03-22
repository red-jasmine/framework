<?php

namespace RedJasmine\Article\Application\Services\Article;

use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Repositories\ArticleReadRepositoryInterface;
use RedJasmine\Article\Domain\Repositories\ArticleRepositoryInterface;
use RedJasmine\Article\Domain\Transformer\ArticleTransformer;
use RedJasmine\Support\Application\ApplicationService;

class ArticleApplicationService extends ApplicationService
{
    public function __construct(
        public ArticleRepositoryInterface $repository,
        public ArticleReadRepositoryInterface $readRepository,
        public ArticleTransformer $transformer
    ) {
    }

    protected static string $modelClass = Article::class;


}