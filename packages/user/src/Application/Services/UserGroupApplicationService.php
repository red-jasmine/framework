<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\User\Domain\Models\UserTagCategory;
use RedJasmine\User\Domain\Repositories\UserTagCategoryReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserTagCategoryRepositoryInterface;
use RedJasmine\User\Domain\Transformers\UserTagCategoryTransformer;

class UserGroupApplicationService extends ApplicationService
{

    public function __construct(
        protected UserTagCategoryRepositoryInterface $repository,
        protected UserTagCategoryReadRepositoryInterface $readRepository,
        protected UserTagCategoryTransformer $transformer
    ) {
    }

    protected static string $modelClass = UserTagCategory::class;


    public function tree(Query $query) : array
    {
        return $this->readRepository->tree($query);
    }
}