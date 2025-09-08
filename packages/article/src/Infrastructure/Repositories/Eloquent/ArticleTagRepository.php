<?php

namespace RedJasmine\Article\Infrastructure\Repositories\Eloquent;


use RedJasmine\Article\Domain\Models\ArticleTag;
use RedJasmine\Article\Domain\Repositories\ArticleTagRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ArticleTagRepository extends Repository implements ArticleTagRepositoryInterface
{

    protected static string $modelClass = ArticleTag::class;


}
