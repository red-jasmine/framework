<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('socialite_users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('app_id', 64)->comment('应用');
            $table->string('provider', 32)->comment('渠道');
            $table->string('client_id', 64)->comment('应用ID');
            $table->string('identity', 64)->comment('身份');
            $table->string('owner_type', 64)->nullable()->comment('绑定用户类型');
            $table->string('owner_id', 64)->nullable()->comment('绑定用户ID');
            $table->timestamps();
            $table->unique(['provider', 'identity', 'client_id', 'app_id'], 'uk_app_provider_user');
            $table->comment('第三方用户表');
        });
    }

    public function down()
    {
        Schema::dropIfExists('socialite_users');
    }
};
