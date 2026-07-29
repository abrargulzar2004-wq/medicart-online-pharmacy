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
        Schema::table('products', function (Blueprint $table) {
            $table->string('batch_number')->nullable()->after('sku');
            $table->date('manufacturing_date')->nullable()->after('manufacturer');
            $table->boolean('is_featured')->default(0)->after('status');
            $table->boolean('is_new_arrival')->default(0)->after('is_featured');
            $table->boolean('is_best_seller')->default(0)->after('is_new_arrival');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['batch_number', 'manufacturing_date', 'is_featured', 'is_new_arrival', 'is_best_seller']);
        });
    }
};
