<?php

namespace RedJasmine\Ecommerce;

use Illuminate\Support\ServiceProvider;
use RedJasmine\Ecommerce\Domain\Models\Casts\AmountCastTransformer;
use RedJasmine\Ecommerce\Domain\Models\Casts\PromiseServicesCastTransformer;
use RedJasmine\Ecommerce\Domain\Models\Casts\PromiseServiceValueCastTransformer;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\PromiseServices;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\PromiseServiceValue;
use RedJasmine\Support\Casts\UserInterfaceCastTransformer;
use RedJasmine\Support\Contracts\UserInterface;

class EcommercePackageServiceProvider extends ServiceProvider
{


    public function register() : void
    {


    }

    public function boot() : void
    {
        $config = $this->app->make('config');

        $config->set('data.casts.' . Amount::class, AmountCastTransformer::class);
        $config->set('data.transformers.' . Amount::class, AmountCastTransformer::class);


        $config->set('data.casts.' . PromiseServiceValue::class, PromiseServiceValueCastTransformer::class);
        $config->set('data.transformers.' . PromiseServiceValue::class, PromiseServiceValueCastTransformer::class);


        $config->set('data.casts.' . PromiseServices::class, PromiseServicesCastTransformer::class);
        $config->set('data.transformers.' . PromiseServices::class, PromiseServicesCastTransformer::class);

        $config->set('data.casts.' . UserInterface::class, UserInterfaceCastTransformer::class);
        $config->set('data.transformers.' . UserInterface::class, UserInterfaceCastTransformer::class);

    }
}
