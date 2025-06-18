<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Queries;

use RedJasmine\Distribution\Application\Promoter\Services\PromoterApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

class FindPromotersByOwnerQueryHandler extends QueryHandler
{
    public function __construct(
        protected PromoterApplicationService $service
    ) {
    }

    public function handle(FindPromotersByOwnerQuery $query)
    {
        return $this->service->readRepository->withQuery(function ($query) {
            return $query->onlyOwner($query->owner)
                ->when($query->name, function ($query) use ($query) {
                    return $query->where('name', 'like', "%{$query->name}%");
                });
        })->paginate($query);
    }
} 