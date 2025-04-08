<?php

namespace RedJasmine\Community\Infrastructure\Repositories\Eloquent;


use RedJasmine\Community\Domain\Models\TopicTag;
use RedJasmine\Community\Domain\Repositories\TopicTagRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class TopicTagRepository extends EloquentRepository implements TopicTagRepositoryInterface
{

    protected static string $eloquentModelClass = TopicTag::class;


}
