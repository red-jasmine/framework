<?php

namespace RedJasmine\Community\Infrastructure\Repositories\Eloquent;


use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\Comnunity\Domain\Repositories\TopicCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class TopicCategoryRepository extends Repository implements TopicCategoryRepositoryInterface
{

    protected static string $modelClass = TopicCategory::class;

    public function findByName($name) : ?TopicCategory
    {
        return static::$modelClass::where('name', $name)->first();
    }


}
