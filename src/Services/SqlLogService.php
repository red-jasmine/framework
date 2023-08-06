<?php

namespace RedJasmine\Support\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * SQL 记录
 */
class SqlLogService
{
    public static function boot() : void
    {
        DB::listen(function ($query) {
            try {
                $sql = str_replace("?", "'%s'", $query->sql);
                $sql = vsprintf($sql, $query->bindings ?? []);
            } catch (Throwable $e) {
                $sql = '';
            }
            $data = [
                'time'           => $query->time,
                'connectionName' => $query->connectionName,
                'sql'            => $sql,
            ];
            Log::info('SQL', $data);
        });

    }
}
