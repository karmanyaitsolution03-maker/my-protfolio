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
        Schema::table('visits', function (Blueprint $table) {
            $table->unsignedInteger('visit_count')->default(1)->after('referrer');
            $table->dropIndex(['ip_address']);
            $table->unique('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropUnique(['ip_address']);
            $table->index('ip_address');
            $table->dropColumn('visit_count');
        });
    }
};
