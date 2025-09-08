<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Support\Http\Controllers\UserOwnerTools;
use RedJasmine\Wallet\Application\Services\Wallet\Queries\FindByOwnerTypeQuery;
use RedJasmine\Wallet\Application\Services\Wallet\WalletApplicationService;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\UI\Http\User\Api\Resources\WalletResource;

class WalletController extends Controller
{
    protected static string $resourceClass = WalletResource::class;
    protected static string $modelClass    = Wallet::class;


    public function __construct(
        protected WalletApplicationService $service,
    ) {
        // 设置查询作用域
        $this->service->repository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    /**
     * 根据类型查询当前用户钱包详情
     */
    public function wallet(Request $request, string $type) : WalletResource
    {


        $query        = new FindByOwnerTypeQuery();
        $query->owner = $this->getOwner();
        $query->type  = $type;

        $wallet = $this->service->findByOwnerType($query);

        // $command = new  WalletTransactionCommand;
        // $command->setKey($wallet->id);
        // $command->title           = '测试';
        // $command->direction       = AmountDirectionEnum::INCOME;
        // $command->transactionType = TransactionTypeEnum::RECHARGE;
        // $command->amount          = Money::parse(100, $wallet->balance->getCurrency());
        // $result                   = $this->service->transaction($command);

        return new WalletResource($wallet);
    }


    public function authorize($ability, $arguments = []) : bool
    {
        return true;
    }
}
