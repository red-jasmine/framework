<?php

namespace RedJasmine\Distribution\UI\Http\User\Api\Controllers;

use RedJasmine\Distribution\Application\Promoter\Services\PromoterApplicationService;
use RedJasmine\Distribution\Application\Promoter\Services\Queries\FindByOwnerQuery;
use RedJasmine\Distribution\Domain\Models\Promoter;

trait HasPromoter
{
    protected function getPromoter() : Promoter
    {
        $promoterApplicationService = app(PromoterApplicationService::class);
        $query = FindByOwnerQuery::from(['owner' => $this->getOwner()]);
        return $promoterApplicationService->findByOwner($query);
    }
}