<?php

namespace RedJasmine\Article\Infrastructure\Repositories\Eloquent;


use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Repositories\ArticleRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ArticleRepository extends Repository implements ArticleRepositoryInterface
{

    protected static string $modelClass = Article::class;


}
