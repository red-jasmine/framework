<?php

namespace RedJasmine\Support\Application;

use Filament\Support\Concerns\Macroable;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Foundation\Hook\HasHooks;

/**
 * TODO
 * @property RepositoryInterface $repository
 * @property ReadRepositoryInterface $readRepository
 */
abstract class ApplicationService
{

    use HasHooks;

    use Macroable;

    /**
     * @var string
     */
    protected static string $modelClass;


    protected static array $commands = [];


    public static function setCommand($command, $handler) : void
    {
        static::$commands[$command] = $handler;
    }


    protected static array $queries = [];


    public static function setQuery($query, $handler) : void
    {
        static::$queries[$query] = $handler;
    }


}