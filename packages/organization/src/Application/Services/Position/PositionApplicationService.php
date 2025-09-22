<?php

namespace RedJasmine\Organization\Application\Services\Position;

use RedJasmine\Organization\Domain\Models\Position;
use RedJasmine\Organization\Domain\Repositories\PositionRepositoryInterface;
use RedJasmine\Organization\Domain\Transformer\PositionTransformer;
use RedJasmine\Support\Application\ApplicationService;

class PositionApplicationService extends ApplicationService
{
    public function __construct(
        public PositionRepositoryInterface $repository,
        public PositionTransformer $transformer
    ) {
    }

    protected static string $modelClass = Position::class;
}


