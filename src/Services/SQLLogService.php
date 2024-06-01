<?php

namespace RedJasmine\Support\Services;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * SQL 记录
 */
class SQLLogService
{
    public static function register() : void
    {


        DB::listen(function (QueryExecuted $query) {
            try {
                $sql = str_replace("?", "'%s'", $query->sql);
                $sql = vsprintf($sql, $query->bindings ?? []);
            } catch (Throwable $e) {
                $sql = '';
            }

            $data = [
                'time'           => $query->time,
                'connectionName' => $query->connectionName,
                'host'           => $query->connection->getConfig('host'),
                'database'       => $query->connection->getConfig('database'),
                'sql'            => $sql,
            ];

            Log::build([
                           'driver' => 'daily',
                           'path'   => storage_path('logs/sql.log'),
                           'level'  => env('LOG_LEVEL', 'debug'),
                           'days'   => 14,
                       ])->info('SQL', $data);
        });

    }
}
