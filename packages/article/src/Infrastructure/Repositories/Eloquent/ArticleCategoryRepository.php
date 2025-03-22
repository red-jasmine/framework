<?php

namespace RedJasmine\Article\Infrastructure\Repositories\Eloquent;


use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\Article\Domain\Repositories\ArticleCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ArticleCategoryRepository extends EloquentRepository implements ArticleCategoryRepositoryInterface
{

    protected static string $eloquentModelClass = ArticleCategory::class;

    public function findByName($name) : ?ArticleCategory
    {
        return static::$eloquentModelClass::where('name', $name)->first();
    }


}
