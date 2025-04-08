<?php

namespace RedJasmine\Community\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Comnunity\Domain\Repositories\TopicReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
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
     *
     * @param  Query|null  $query
     *
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
        return ['extension', 'category'];
    }


}
