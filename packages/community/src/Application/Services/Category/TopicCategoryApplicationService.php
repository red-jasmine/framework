<?php

namespace RedJasmine\Community\Application\Services\Category;

use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\Community\Domain\Transformer\TopicCategoryTransformer;
use RedJasmine\Comnunity\Domain\Repositories\TopicCategoryReadRepositoryInterface;
use RedJasmine\Comnunity\Domain\Repositories\TopicCategoryRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;

class TopicCategoryApplicationService extends ApplicationService
{

    public function __construct(
        public TopicCategoryRepositoryInterface $repository,
        public TopicCategoryReadRepositoryInterface $readRepository,
        public TopicCategoryTransformer $transformer

    ) {
    }

    protected static string $modelClass = TopicCategory::class;


    public function tree(Query $query) : array
    {

        return $this->readRepository->tree($query);
    }

}