<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\User\Domain\Models\UserGroup;
use RedJasmine\User\Domain\Repositories\UserTagCategoryReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserTagCategoryRepositoryInterface;
use RedJasmine\User\Domain\Transformers\UserTagCategoryTransformer;

class UserTagCategoryApplicationService extends ApplicationService
{

    public function __construct(
        public UserTagCategoryRepositoryInterface $repository,
        public UserTagCategoryReadRepositoryInterface $readRepository,
        public UserTagCategoryTransformer $transformer
    ) {
    }

    protected static string $modelClass = UserGroup::class;


    public function tree(Query $query) : array
    {
        return $this->readRepository->tree($query);
    }
}