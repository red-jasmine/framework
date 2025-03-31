<?php

namespace RedJasmine\Community\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\Comnunity\Domain\Repositories\TopicCategoryReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class TopicCategoryReadRepository extends QueryBuilderReadRepository implements TopicCategoryReadRepositoryInterface
{

    use HasTree;

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = TopicCategory::class;


}
