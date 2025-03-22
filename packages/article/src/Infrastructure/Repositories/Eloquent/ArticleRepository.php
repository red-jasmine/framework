<?php

namespace RedJasmine\Article\Infrastructure\Repositories\Eloquent;


use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Repositories\ArticleRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ArticleRepository extends EloquentRepository implements ArticleRepositoryInterface
{

    protected static string $eloquentModelClass = Article::class;


}
