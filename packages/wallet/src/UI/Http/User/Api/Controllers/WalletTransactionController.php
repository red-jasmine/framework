<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Controllers;

use RedJasmine\Support\Http\Controllers\UserOwnerTools;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;
use RedJasmine\Wallet\Application\Queries\Transaction\WalletTransactionDetailQuery;
use RedJasmine\Wallet\Application\Queries\Transaction\WalletTransactionListQuery;
use RedJasmine\Wallet\Application\Services\Transaction\Queries\UserWalletTransactionFindQuery;
use RedJasmine\Wallet\Application\Services\Transaction\Queries\UserWalletTransactionPaginateQuery;
use RedJasmine\Wallet\Application\Services\Transaction\WalletTransactionApplicationService;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\UI\Http\User\Api\Resources\WalletTransactionResource;

class WalletTransactionController extends Controller
{
    protected static string $resourceClass      = WalletTransactionResource::class;
    protected static string $modelClass         = WalletTransaction::class;
    protected static string $paginateQueryClass = UserWalletTransactionPaginateQuery::class;
    protected static string $findQueryClass     = UserWalletTransactionFindQuery::class;

    use RestQueryControllerActions;


    public function __construct(
        protected WalletTransactionApplicationService $service,
    ) {
        // 设置查询作用域
        $this->service->repository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }


    public function authorize($ability, $arguments = []) : bool
    {
        return true;
    }
}
