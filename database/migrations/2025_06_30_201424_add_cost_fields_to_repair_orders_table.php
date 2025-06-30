<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('repair_orders', function (Blueprint $table) {
            $table->decimal('estimated_cost', 10, 2)->nullable()->after('status')->comment('التكلفة المقدرة للإصلاح');
            $table->decimal('final_cost', 10, 2)->nullable()->after('estimated_cost')->comment('التكلفة النهائية للإصلاح');
            $table->text('notes')->nullable()->after('final_cost')->comment('ملاحظات إضافية على الطلب');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repair_orders', function (Blueprint $table) {
            $table->dropColumn(['estimated_cost', 'final_cost', 'notes']);
        });
    }
};
