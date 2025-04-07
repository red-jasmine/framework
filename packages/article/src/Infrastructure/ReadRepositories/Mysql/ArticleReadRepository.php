<?php

namespace RedJasmine\Article\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Repositories\ArticleReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ArticleReadRepository extends QueryBuilderReadRepository implements ArticleReadRepositoryInterface
{


    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Article::class;


    /**
     * 过滤器
     * @return array
     */
    protected function allowedFilters(?Query $query = null) : array
    {
        return [
            AllowedFilter::exact('category_id'),
            AllowedFilter::partial('keyword', 'title'),
        ];

    }

    protected function allowedIncludes(?Query $query = null) : ?array
    {
        return ['extension', 'tags', 'category'];
    }


}
