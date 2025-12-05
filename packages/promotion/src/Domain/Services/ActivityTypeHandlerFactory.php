<?php

namespace RedJasmine\Promotion\Domain\Services;

use Closure;
use RedJasmine\Promotion\Domain\Contracts\ActivityTypeHandlerInterface;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityTypeEnum;
use RedJasmine\Promotion\Domain\Services\ActivityTypeHandlers\DiscountActivityHandler;
use RedJasmine\Promotion\Domain\Services\ActivityTypeHandlers\FlashSaleActivityHandler;
use RedJasmine\Promotion\Domain\Services\ActivityTypeHandlers\FullReductionActivityHandler;
use RedJasmine\Promotion\Domain\Services\ActivityTypeHandlers\GroupBuyingActivityHandler;
use RedJasmine\Support\Foundation\Manager\ServiceManager;

/**
 * 活动类型处理器工厂
 * 
 * 负责创建和管理不同类型的活动处理器
 * 基于 ServiceManager 提供配置管理、懒加载、自定义创建器等功能
 */
class ActivityTypeHandlerFactory extends ServiceManager
{
    /**
     * 内置的活动类型处理器映射
     */
    protected const  PROVIDERS = [
        ActivityTypeEnum::FLASH_SALE->value => FlashSaleActivityHandler::class,
        ActivityTypeEnum::GROUP_BUYING->value => GroupBuyingActivityHandler::class,
        ActivityTypeEnum::DISCOUNT->value => DiscountActivityHandler::class,
        ActivityTypeEnum::FULL_REDUCTION->value => FullReductionActivityHandler::class,
        // 可以继续添加更多活动类型处理器
    ];
    
    /**
     * 单例实例
     */
    protected static ?self $instance = null;
    
    /**
     * 获取单例实例
     */
    public static function getInstance(array $config = []): self
    {
        if (static::$instance === null) {
            static::$instance = new static($config);
        }
        
        return static::$instance;
    }
    
    /**
     * 创建活动类型处理器（兼容旧接口）
     * 
     * @param string|ActivityTypeEnum $activityType
     * @return ActivityTypeHandlerInterface
     * @throws \InvalidArgumentException
     */
    public static function make(string|ActivityTypeEnum $activityType): ActivityTypeHandlerInterface
    {
        $type = $activityType instanceof ActivityTypeEnum ? $activityType->value : $activityType;
        
        return static::getInstance()->createActivityHandler($type);
    }
    

    
    /**
     * 创建活动处理器实例
     * 
     * @param string $name
     * @return ActivityTypeHandlerInterface
     */
    public function createActivityHandler(string $name): ActivityTypeHandlerInterface
    {
        $handler = parent::create($name);
        
        if (!$handler instanceof ActivityTypeHandlerInterface) {
            throw new \RuntimeException("活动处理器必须实现 ActivityTypeHandlerInterface 接口");
        }
        
        return $handler;
    }
    

    
    /**
     * 构建处理器实例
     * 
     * @param string $provider
     * @param array $config
     * @return ActivityTypeHandlerInterface
     */
    public function buildProvider(string $provider, array $config): ActivityTypeHandlerInterface
    {
        if (!class_exists($provider)) {
            throw new \RuntimeException("活动处理器类不存在: {$provider}");
        }
        
        // 使用Laravel容器创建实例，支持依赖注入
        return app($provider, ['config' => $config]);
    }
    
    /**
     * 注册新的活动类型处理器
     * 
     * @param string $activityType
     * @param string|Closure $handler
     * @return void
     */
    public static function register(string $activityType, string|Closure $handler): void
    {
        if ($handler instanceof Closure) {
            static::getInstance()->extend($activityType, $handler);
        } else {
            // 添加到实例配置中
            $instance = static::getInstance();
            $config = $instance->getConfig();
            $config[$activityType] = ['provider' => $handler];
            $instance->setConfig($config);
        }
    }
    
    /**
     * 批量注册活动类型处理器
     * 
     * @param array $handlers
     * @return void
     */
    public static function registerMany(array $handlers): void
    {
        foreach ($handlers as $type => $handler) {
            static::register($type, $handler);
        }
    }
    
    /**
     * 获取所有已注册的活动类型
     * 
     * @return array
     */
    public static function getRegisteredTypes(): array
    {
        return array_keys(static::getInstance()->providers());
    }
    
    /**
     * 检查活动类型是否已注册
     * 
     * @param string $activityType
     * @return bool
     */
    public static function isRegistered(string $activityType): bool
    {
        return static::getInstance()->isValidProvider($activityType) || 
               isset(static::$customCreators[static::class][strtolower($activityType)]);
    }
    
    /**
     * 获取活动类型的处理器类名
     * 
     * @param string $activityType
     * @return string|null
     */
    public static function getHandlerClass(string $activityType): ?string
    {
        $providers = static::getInstance()->providers();
        return $providers[$activityType] ?? null;
    }
    
    /**
     * 移除活动类型处理器
     * 
     * @param string $activityType
     * @return void
     */
    public static function unregister(string $activityType): void
    {
        $instance = static::getInstance();
        
        // 从配置中移除
        $config = $instance->getConfig();
        unset($config[$activityType]);
        $instance->setConfig($config);
        
        // 从已解析的实例中移除
        $resolved = $instance->getResolvedProviders();
        unset($resolved[strtolower($activityType)]);
        
        // 从自定义创建器中移除
        unset(static::$customCreators[static::class][strtolower($activityType)]);
    }
    
    /**
     * 获取所有活动类型处理器的扩展字段
     * 
     * @return array
     */
    public static function getAllExtensionFields(): array
    {
        $allFields = [];
        $types = static::getRegisteredTypes();
        
        foreach ($types as $type) {
            try {
                $handler = static::create($type);
                $allFields[$type] = $handler->getExtensionFields();
            } catch (\Exception $e) {
                // 忽略创建失败的处理器
                continue;
            }
        }
        
        return $allFields;
    }
    
    /**
     * 获取所有活动类型的默认规则
     * 
     * @return array
     */
    public static function getAllDefaultRules(): array
    {
        $allRules = [];
        $types = static::getRegisteredTypes();
        
        foreach ($types as $type) {
            try {
                $handler = static::create($type);
                $allRules[$type] = $handler->getDefaultRules();
            } catch (\Exception $e) {
                // 忽略创建失败的处理器
                continue;
            }
        }
        
        return $allRules;
    }
    
    /**
     * 重置单例实例（主要用于测试）
     * 
     * @return void
     */
    public static function resetInstance(): void
    {
        static::$instance = null;
    }
    
    /**
     * 获取处理器的配置信息
     * 
     * @param string $activityType
     * @return array
     */
    public static function getHandlerConfig(string $activityType): array
    {
        $instance = static::getInstance();
        $config = $instance->getConfig();
        
        return $config[$activityType] ?? [];
    }
    
    /**
     * 设置处理器配置
     * 
     * @param string $activityType
     * @param array $config
     * @return void
     */
    public static function setHandlerConfig(string $activityType, array $config): void
    {
        $instance = static::getInstance();
        $allConfig = $instance->getConfig();
        $allConfig[$activityType] = array_merge($allConfig[$activityType] ?? [], $config);
        $instance->setConfig($allConfig);
    }
}
