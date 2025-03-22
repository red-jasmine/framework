<?php

namespace RedJasmine\Article\Domain\Repositories;

use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface ArticleCategoryRepositoryInterface extends RepositoryInterface
{

    public function findByName($name) : ?ArticleCategory;

}