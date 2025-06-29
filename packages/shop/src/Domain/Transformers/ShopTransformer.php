<?php

namespace RedJasmine\Shop\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Shop\Domain\Models\Shop;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\User\Domain\Transformers\UserTransformer;

class ShopTransformer implements TransformerInterface
{
    public function __construct(
        protected UserTransformer $userTransformer
    ) {
    }

    /**
     * @param mixed $data
     * @param Shop $model
     * @return Shop
     */
    public function transform($data, $model): Shop
    {
        $this->userTransformer->transform($data, $model);
        return $model;
    }
} 