<?php

namespace RedJasmine\Article\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\Article\Domain\Repositories\ArticleCategoryReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ArticleCategoryReadRepository extends QueryBuilderReadRepository implements ArticleCategoryReadRepositoryInterface
{

    use HasTree;

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = ArticleCategory::class;


}
