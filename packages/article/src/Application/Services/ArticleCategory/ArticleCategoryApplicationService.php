<?php

namespace RedJasmine\Article\Application\Services\ArticleCategory;

use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\Article\Domain\Repositories\ArticleCategoryReadRepositoryInterface;
use RedJasmine\Article\Domain\Repositories\ArticleCategoryRepositoryInterface;
use RedJasmine\Article\Domain\Transformer\ArticleCategoryTransformer;
use RedJasmine\Support\Application\ApplicationService;

class ArticleCategoryApplicationService extends ApplicationService
{

    public function __construct(
        public ArticleCategoryRepositoryInterface $repository,
        public ArticleCategoryReadRepositoryInterface $readRepository,
        public ArticleCategoryTransformer $transformer

    ) {
    }

    protected static string $modelClass = ArticleCategory::class;

}