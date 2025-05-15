<?php

namespace RedJasmine\Support\Domain\Models\Traits;

/**
 * 支持外部指定链接
 */
trait HasDefaultConnection
{
    public static ?string $defaultConnection = null;

    public static function getDefaultConnection() : ?string
    {
        return static::$defaultConnection;
    }

    public static function setDefaultConnection(?string $defaultConnection) : void
    {
        static::$defaultConnection = $defaultConnection;
    }


    /**
     * Get the current connection name for the model.
     *
     * @return string|null
     */
    public function getConnectionName()
    {
        return static::$defaultConnection ?? $this->connection;
    }
}