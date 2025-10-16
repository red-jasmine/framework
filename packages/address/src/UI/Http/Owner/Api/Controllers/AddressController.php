<?php

namespace RedJasmine\Address\UI\Http\Owner\Api\Controllers;

use RedJasmine\Address\Application\Services\AddressApplicationService;
use RedJasmine\Address\Application\Services\Queries\AddressPaginateQuery;
use RedJasmine\Address\Domain\Data\AddressData;
use RedJasmine\Address\Domain\Models\Address;
use RedJasmine\Address\UI\Http\Owner\Api\Resources\AddressResource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class AddressController extends Controller
{

    use RestControllerActions;

    public static string $modelClass = Address::class;

    public static string $dataClass = AddressData::class;

    public static string $resourceClass = AddressResource::class;

    public static string $paginateQueryClass = AddressPaginateQuery::class;


    public function authorize($ability, $arguments = [])
    {
        return true;

    }

    public function __construct(
        public AddressApplicationService $service
    ) {
        // 使用统一仓库的查询回调功能
        $this->service->repository->withQuery(function ($query) {
            return $query->onlyOwner($this->getOwner());
        });
    }

}
