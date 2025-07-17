<?php

namespace RedJasmine\Wallet\Application\Services\Wallet\Queries;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Support\Application\Queries\QueryHandler;
use RedJasmine\Wallet\Application\Services\Wallet\Commands\WalletCreateCommand;
use RedJasmine\Wallet\Application\Services\Wallet\WalletApplicationService;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Exceptions\WalletException;

class FindByOwnerTypeQueryHandler extends QueryHandler
{

    public function __construct(
        public WalletApplicationService $service
    ) {
    }

    /**
     * @param  FindByOwnerTypeQuery  $query
     *
     * @return Wallet|null
     * @throws WalletException
     */
    public function handle(FindByOwnerTypeQuery $query) : ?Wallet
    {
        $walletConfig = $this->service->walletService->getWalletConfig($query->type);

        if (!$walletConfig->isAllowUserType($query->owner)) {
            throw new WalletException('用户类型不允许使用此钱包类型');
        }

        try {
            $wallet = $this->service->readRepository->findByOwnerType($query->owner, $query->type);
        } catch (ModelNotFoundException) {
            $command        = new WalletCreateCommand();
            $command->owner = $query->owner;
            $command->type  = $query->type;
            $wallet         = $this->service->create($command);
        }

        return $wallet;
    }
}