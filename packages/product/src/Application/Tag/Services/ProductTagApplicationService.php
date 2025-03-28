<?php

namespace RedJasmine\Product\Application\Tag\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagReadRepositoryInterface;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagRepositoryInterface;
use RedJasmine\Product\Exceptions\CategoryException;
use RedJasmine\Support\Application\ApplicationService;

class ProductTagApplicationService extends ApplicationService
{
    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.tag';

    protected static string $modelClass = ProductTag::class;


    public function __construct(
        public ProductTagRepositoryInterface $repository,
        public ProductTagReadRepositoryInterface $readRepository
    ) {
    }

    public function newModel($data = null) : Model
    {
        if ($model = $this->readRepository
            ->withQuery(fn($query) => $query->onlyOwner($data->owner))
            ->findByName($data->name)) {
            throw new CategoryException('名称存在重复');
        }
        return parent::newModel($data);
    }
}
