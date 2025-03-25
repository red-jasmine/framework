<?php

namespace RedJasmine\Article\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Repositories\ArticleReadRepositoryInterface;
use RedJasmine\Support\Infrastrucdture\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ArticleReadRepository extends QueryBuilderReadRepository implements ArticleReadRepositoryInterface
{

    use HasTree;

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Article::class;


    /**
     * 过滤器
     * @return array
     */
    protected function filters() : array
    {
        return [
            AllowedFilter::exact('category_id'),
        ];

    }


    protected ?array $allowedIncludes = [
        'content'
    ];


}
