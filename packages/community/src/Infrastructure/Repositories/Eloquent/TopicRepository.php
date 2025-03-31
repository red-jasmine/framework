<?php

namespace RedJasmine\Community\Infrastructure\Repositories\Eloquent;


use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Comnunity\Domain\Repositories\TopicRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class TopicRepository extends EloquentRepository implements TopicRepositoryInterface
{

    protected static string $eloquentModelClass = Topic::class;


}
