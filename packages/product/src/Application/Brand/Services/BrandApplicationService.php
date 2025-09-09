<?php

namespace RedJasmine\Product\Application\Brand\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandCreateCommand;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandDeleteCommand;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandUpdateCommand;
use RedJasmine\Product\Application\Brand\Services\Handlers\BrandCreateCommandHandler;
use RedJasmine\Product\Application\Brand\Services\Handlers\BrandDeleteCommandHandler;
use RedJasmine\Product\Application\Brand\Services\Handlers\BrandUpdateCommandHandler;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 * @method Brand create(BrandCreateCommand $command)
 * @method void update(BrandUpdateCommand $command)
 * @method void delete(BrandDeleteCommand $command)
 */
class BrandApplicationService extends ApplicationService
{


    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.brand';
    /**
     * 定义模型
     * @var string
     */
    protected static string $modelClass = Brand::class;

    /**
     * 仓库
     *
     * @param  BrandRepositoryInterface  $repository
     * @param  BrandReadRepositoryInterface  $readRepository
     */
    public function __construct(
        public BrandRepositoryInterface $repository,
        public BrandReadRepositoryInterface $readRepository,
    ) {
    }

    public function newModel($data = null) : Model
    {
        if ($this->repository->findByName($data->name)) {

            throw ValidationException::withMessages(
                ['name' => [__('名称存在重复')]]
            );

        }
        return parent::newModel($data);
    }

    public function isAllowUse(int $id) : bool
    {
        return (bool) ($this->readRepository->find(FindQuery::make($id))?->isAllowUse());
    }


}
