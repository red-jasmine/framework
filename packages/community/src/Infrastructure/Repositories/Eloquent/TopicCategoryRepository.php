<?php

namespace RedJasmine\Community\Infrastructure\Repositories\Eloquent;


use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\Comnunity\Domain\Repositories\TopicCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class TopicCategoryRepository extends EloquentRepository implements TopicCategoryRepositoryInterface
{

    protected static string $eloquentModelClass = TopicCategory::class;

    public function findByName($name) : ?TopicCategory
    {
        return static::$eloquentModelClass::where('name', $name)->first();
    }


}
