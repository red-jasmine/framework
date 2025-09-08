<?php

namespace RedJasmine\Community\Infrastructure\Repositories\Eloquent;


use RedJasmine\Community\Domain\Models\TopicTag;
use RedJasmine\Community\Domain\Repositories\TopicTagRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class TopicTagRepository extends Repository implements TopicTagRepositoryInterface
{

    protected static string $modelClass = TopicTag::class;


}
