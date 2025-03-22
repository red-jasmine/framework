<?php

namespace RedJasmine\Article\Infrastructure\Repositories\Eloquent;


use RedJasmine\Article\Domain\Models\ArticleTag;
use RedJasmine\Article\Domain\Repositories\ArticleTagRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ArticleTagRepository extends EloquentRepository implements ArticleTagRepositoryInterface
{

    protected static string $eloquentModelClass = ArticleTag::class;


}
