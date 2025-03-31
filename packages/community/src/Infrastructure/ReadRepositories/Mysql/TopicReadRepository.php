<?php

namespace RedJasmine\Community\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Repositories\ArticleReadRepositoryInterface;
use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Comnunity\Domain\Repositories\TopicReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class TopicReadRepository extends QueryBuilderReadRepository implements TopicReadRepositoryInterface
{


    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Topic::class;


    /**
     * 过滤器
     * @return array
     */
    protected function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('category_id'),
            AllowedFilter::partial('keyword', 'title'),
        ];

    }

    protected function allowedIncludes() : ?array
    {
        return ['content', 'category'];
    }


}
