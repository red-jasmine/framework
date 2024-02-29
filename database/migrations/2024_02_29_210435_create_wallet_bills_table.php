<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('wallet_bills', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('wallet_id')->comment('钱包ID');
            $table->unsignedTinyInteger('bill_type')->default(1)->comment('加减类型');
            $table->decimal('amount', 12)->default(0)->comment('金额');
            $table->string('business_type', 30)->nullable()->comment('业务类型');
            $table->unsignedBigInteger('business_no')->nullable()->comment('业务单号');
            $table->string('business_remark')->nullable()->comment('业务备注');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('wallet_bills');
    }
};
