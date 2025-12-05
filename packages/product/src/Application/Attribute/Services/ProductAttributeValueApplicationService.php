<?php

namespace RedJasmine\Product\Application\Attribute\Services;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeValueData;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue;
use RedJasmine\Product\Domain\Attribute\Repositories\ProductAttributeValueRepositoryInterface;
use RedJasmine\Product\Domain\Attribute\Transformer\ProductAttributeValueTransformer;
use RedJasmine\Product\Exceptions\ProductAttributeException;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @method ProductAttributeValue create(ProductAttributeValueData $command)
 * @method void update(ProductAttributeValueData $command)
 */
class ProductAttributeValueApplicationService extends ApplicationService
{


    /**
     * 钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.product-attribute-value';


    protected static string $modelClass = ProductAttributeValue::class;

    public function __construct(
        public ProductAttributeValueRepositoryInterface $repository,
        public ProductAttributeValueTransformer $transformer,
    ) {

    }

    public function newModel($data = null) : Model
    {
        if ($model = $this->repository->findByNameInAttribute($data->aid, $data->name)) {
            throw new ProductAttributeException('名称已存在');
            return $model;
        }
        return parent::newModel($data);
    }

    /**
     * 钩子配置
     *
     * 注意：验证逻辑已迁移到 Domain 层的 Laravel ValidationRule
     * 通过 ProductAttributeValueData::rules() 方法定义验证规则
     * 验证会在 Data::from() 时自动执行
     *
     * @return array
     */
    protected function hooks() : array
    {
        return [
            // 验证逻辑已迁移到 Domain 层的 Laravel ValidationRule
            // 通过 ProductAttributeValueData::rules() 方法定义
        ];
    }


}
