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


        DB::listen(static function (QueryExecuted $query) {
            try {
                $sql = str_replace("?", "'%s'", $query->sql);
                $sql = vsprintf($sql, $query->bindings ?? []);
            } catch (Throwable $e) {
                $sql = '';
            }

            $data = [
                'sql'            => $sql,
                'connectionName' => $query->connectionName,
                'host'           => $query->connection->getConfig('host'),
                'database'       => $query->connection->getConfig('database'),
                'time'           => $query->time,
            ];

            Log::debug('SQL',$data);
        });

    }
}
