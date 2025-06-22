<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApprovalMethodEnum;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('promoter_applies', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('promoter_id')->comment('分销员ID');
            $table->unsignedTinyInteger('level')->default(1)->comment('等级');
            $table->string('apply_type', 32)->comment(PromoterApplyTypeEnum::comments('类型'));
            $table->string('apply_method', 32)->comment(PromoterApplyMethodEnum::comments('申请方式'));
            $table->string('approval_method', 32)->comment(PromoterApprovalMethodEnum::comments('审核方式'));
            $table->string('approval_status', 32)->comment(ApprovalStatusEnum::comments('审批状态'));
            $table->timestamp('apply_at')->comment('申请时间');
            $table->timestamp('approval_at')->nullable()->comment('审批时间');
            $table->string('approval_reason')->nullable()->comment('审核原因');
            $table->json('extra')->nullable()->comment('扩展字段');
            $table->userMorphs('approver', '审批人');
            $table->operator();
            $table->softDeletes();
            $table->comment('分销员申请记录');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('promoter_applies');
    }
};
