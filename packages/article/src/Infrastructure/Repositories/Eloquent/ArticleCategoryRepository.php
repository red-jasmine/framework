<?php

namespace RedJasmine\Article\Infrastructure\Repositories\Eloquent;


use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\Article\Domain\Repositories\ArticleCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ArticleCategoryRepository extends Repository implements ArticleCategoryRepositoryInterface
{

    protected static string $modelClass = ArticleCategory::class;

    public function findByName($name) : ?ArticleCategory
    {
        return static::$modelClass::where('name', $name)->first();
    }


}
