<?php

namespace RedJasmine\Support\Helpers;

class Blueprint
{


    public static function boot() : void
    {
        \Illuminate\Database\Schema\Blueprint::macro('owner', function () {
            $this->string('owner_type', 10)->comment('所有者类型');
            $this->unsignedBigInteger('owner_uid')->comment('所有者UID');
        });

        \Illuminate\Database\Schema\Blueprint::macro('creator', function () {
            $this->string('creator_type', 10)->nullable()->comment('创建者类型');
            $this->unsignedBigInteger('creator_uid')->nullable()->comment('创建者ID');
        });
        \Illuminate\Database\Schema\Blueprint::macro('updater', function () {
            $this->string('updater_type', 10)->nullable()->comment('更新者类型');
            $this->unsignedBigInteger('updater_uid')->nullable()->comment('更新者UID');
        });
    }


}
