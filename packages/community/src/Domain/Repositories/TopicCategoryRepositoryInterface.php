<?php

namespace RedJasmine\Comnunity\Domain\Repositories;

use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface TopicCategoryRepositoryInterface extends RepositoryInterface
{

    public function findByName($name) : ?TopicCategory;

}