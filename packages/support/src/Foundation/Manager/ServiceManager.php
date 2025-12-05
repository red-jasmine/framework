<?php

namespace RedJasmine\Support\Foundation\Manager;

use Closure;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use function strtolower;

abstract class ServiceManager
{

    protected array $config;
    protected array $resolved = [];
    protected const  PROVIDERS = [];
    protected static array $customCreators = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function getConfig() : array
    {
        return $this->config;
    }

    public function providers() : array
    {
        return array_merge(static::PROVIDERS, static::$customCreators[static::class] ?? []);
    }

    public function setConfig(array $config) : ServiceManager
    {
        $this->config = $config;
        return $this;
    }


    public function extend(string $name, Closure $callback) : self
    {
        static::$customCreators[static::class][strtolower($name)] = $callback;

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

        if (isset(self::$customCreators[static::class][$provider])) {
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
        return self::$customCreators[static::class][$name]($config);
    }

    protected function isValidProvider(string $provider) : bool
    {
        return isset(static::PROVIDERS[$provider]);
    }
}
