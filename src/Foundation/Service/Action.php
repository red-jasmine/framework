<?php

namespace RedJasmine\Support\Foundation\Service;


/**
 * @property Service $service
 */
abstract class Action implements ServiceAwareAction
{


    /**
     * The array of booted models.
     *
     * @var array
     */
    protected static array $booted = [];
    /**
     * The array of trait initializers that will be called on each new instance.
     *
     * @var array
     */
    protected static array $traitInitializers = [];

    public function __construct()
    {
        $this->bootIfNotBooted();
        $this->initializeTraits();
    }

    /**
     * Check if the model needs to be booted and if so, do it.
     *
     * @return void
     */
    protected function bootIfNotBooted() : void
    {
        if (!isset(static::$booted[static::class])) {
            static::$booted[static::class] = true;
            static::booting();
            static::boot();
            static::booted();
        }
    }


    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booting()
    {
        //
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot() : void
    {
        static::bootTraits();
    }

    /**
     * Boot all of the bootable traits on the model.
     *
     * @return void
     */
    protected static function bootTraits() : void
    {
        $class = static::class;

        $booted = [];

        static::$traitInitializers[$class] = [];

        foreach (class_uses_recursive($class) as $trait) {
            $method = 'boot' . class_basename($trait);

            if (method_exists($class, $method) && !in_array($method, $booted)) {
                forward_static_call([ $class, $method ]);
                $booted[] = $method;
            }

            if (method_exists($class, $method = 'initialize' . class_basename($trait))) {
                static::$traitInitializers[$class][] = $method;

                static::$traitInitializers[$class] = array_unique(
                    static::$traitInitializers[$class]
                );
            }
        }
    }

    /**
     * Initialize any initializable traits on the model.
     *
     * @return void
     */
    protected function initializeTraits() : void
    {
        foreach (static::$traitInitializers[static::class] as $method) {
            $this->{$method}();
        }
    }

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        //
    }


    use HasPipelines;

    use HasValidatorCombiners;

    use CanUseDatabaseTransactions;

    /**
     * @return Service
     */
    public function getService() : Service
    {
        return $this->service;
    }


    public function setService($service) : static
    {
        $this->service = $service;
        return $this;
    }

}
