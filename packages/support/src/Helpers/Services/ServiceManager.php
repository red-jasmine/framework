<?php

namespace RedJasmine\Support\Helpers\Services;

use Closure;
use Illuminate\Support\Arr;
use function strtolower;
use InvalidArgumentException;

abstract class ServiceManager
{

    protected array $config;
    protected array $resolved = [];
    protected const PROVIDERS = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function getConfig() : array
    {
        return $this->config;
    }

    public function setConfig(array $config) : ServiceManager
    {
        $this->config = $config;
        return $this;
    }

    protected static array $customCreators = [];

    public function extend(string $name, Closure $callback) : self
    {
        static::$customCreators[strtolower($name)] = $callback;

        return $this;
    }

    public function create(string $name)
    {
        $name = strtolower($name);


        if (!isset($this->resolved[$name])) {
            $this->resolved[$name] = $this->createProvider($name);
        }

        return $this->resolved[$name];
    }

    protected function createProvider(string $name)
    {
        $config   = Arr::get($this->config, $name, []);
        $provider = $config['provider'] ?? $name;

        if (isset(self::$customCreators[$provider])) {
            return $this->callCustomCreator($provider, $config);
        }

        if (!$this->isValidProvider($provider)) {
            throw new InvalidArgumentException("Provider [{$name}] not supported.");
        }

        return $this->buildProvider(static::PROVIDERS[$provider] ?? $provider, $config);
    }

    public function getResolvedProviders() : array
    {
        return $this->resolved;
    }

    public function buildProvider(string $provider, array $config)
    {
        return new $provider($config);

    }

    protected function callCustomCreator(string $name, array $config)
    {
        return self::$customCreators[$name]($config);
    }

    protected function isValidProvider(string $provider) : bool
    {
        return isset(static::PROVIDERS[$provider]);
    }
}
