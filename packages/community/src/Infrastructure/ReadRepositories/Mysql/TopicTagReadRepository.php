<?php

namespace RedJasmine\Community\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Community\Domain\Models\TopicTag;
use RedJasmine\Community\Domain\Repositories\TopicTagReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class TopicTagReadRepository extends QueryBuilderReadRepository implements TopicTagReadRepositoryInterface
{

    use HasTree;

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = TopicTag::class;


}
