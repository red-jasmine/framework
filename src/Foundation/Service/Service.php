<?php

namespace RedJasmine\Support\Foundation\Service;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Helpers\ID\Snowflake;

abstract class Service
{


    use HasActions;

    use HasBuildId;

    use WithUserService;

    use WithClientService;

    /**
     * 服务配置
     * @var string|null
     */
    protected static ?string $serviceConfigKey = null;

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
     * 服务的动态配置
     * @var array
     */
    protected array $serviceConfig = [];

    public function __construct()
    {
        $this->bootIfNotBooted();
        $this->initializeConfig();
        $this->initializeTraits();

    }

    protected function initializeConfig() : void
    {
        $this->serviceConfig = Config::get(static::$serviceConfigKey, []);
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


    protected function getServiceTraitsConfig($method)
    {
        if ($key = $this->serviceTraitsConfigKeys()[$method] ?? null) {
            return $this->serviceConfig[$key] ?? null;
        }
        return null;
    }

    protected function serviceTraitsConfigKeys() : array
    {
        return [
            'initializeHasActions' => 'actions',
        ];
    }

    /**
     * Initialize any initializable traits on the model.
     *
     * @return void
     */
    protected function initializeTraits() : void
    {

        foreach (static::$traitInitializers[static::class] as $method) {
            $parameters = $this->getServiceTraitsConfig($method);
            $this->{$method}($parameters);
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

    protected function initializeAction($action, array $config = []) : void
    {
        if ($action instanceof ServiceAwareAction) {
            $action->setService($this);
        }
    }

}
