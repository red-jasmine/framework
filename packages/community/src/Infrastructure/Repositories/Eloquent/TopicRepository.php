<?php

namespace RedJasmine\Community\Infrastructure\Repositories\Eloquent;


use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Comnunity\Domain\Repositories\TopicRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class TopicRepository extends Repository implements TopicRepositoryInterface
{

    protected static string $modelClass = Topic::class;


}
