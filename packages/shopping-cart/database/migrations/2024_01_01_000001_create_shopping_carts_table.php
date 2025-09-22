<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type')->comment('所有者类型');
            $table->string('owner_id')->comment('所有者ID');
            $table->string('market')->default('default')->comment('市场标识');
            $table->enum('status', ['active', 'expired', 'converted', 'cleared'])->default('active')->comment('购物车状态');
            $table->operator();
            $table->comment('购物车');
            $table->index(['owner_id', 'owner_type', 'market',], 'idx_owner_market');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('shopping_carts');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('market')->default('default')->comment('市场');
            $table->string('owner_type')->comment('所属者类型');
            $table->unsignedBigInteger('owner_id')->comment('所属者ID');
            $table->string('status')->default('active')->comment('状态');
            $table->decimal('total_amount', 10, 2)->default(0)->comment('总金额');
            $table->decimal('discount_amount', 10, 2)->default(0)->comment('折扣金额');
            $table->decimal('final_amount', 10, 2)->default(0)->comment('最终金额');
            $table->timestamp('expired_at')->nullable()->comment('过期时间');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['owner_type', 'owner_id', 'market']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopping_carts');
    }
};
