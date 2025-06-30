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
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('order_id');
            $table->unique(['user_id', 'product_id'], 'cart_items_unique');
        });

        // Add foreign key for user_id
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasTable('users')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique('cart_items_unique');
            $table->dropColumn('user_id');
        });
    }
};
