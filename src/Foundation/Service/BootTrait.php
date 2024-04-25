<?php

namespace RedJasmine\Support\Foundation\Service;

trait BootTrait
{

    public function __construct()
    {
        $this->bootIfNotBooted();

        $this->initializeTraits();
    }

    /**
     * Initialize any initialize able traits on the object.
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
    protected static function booting() : void
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

    protected static function booted() : void
    {

    }

    /**
     * Boot all  the bootable traits on the model.
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

            if (method_exists($class, $method) && !in_array($method, $booted, true)) {
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


}
