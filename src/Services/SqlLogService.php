<?php

namespace RedJasmine\Support\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SqlLogService
{
    public static function boot() : void
    {
        if (!config('app.debug')) {
            return;
        }
        $channel = config('red-jasmine.support.sql-log.channel', null);
        DB::listen(static function ($query) use ($channel) {
            try {
                $sql = str_replace("?", "'%s'", $query->sql);
                $sql = vsprintf($sql, $query->bindings ?? []);
            } catch (Throwable $e) {
                $sql = $query->sql;
            }
            $data = [
                'time'           => $query->time,
                'connectionName' => $query->connectionName,
            ];
            Log::channel($channel)->debug($sql, $data);
        });

    }
}
