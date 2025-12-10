<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // FIX: Use Raw SQL to handle ENUM change robustly, including data conversion
        
        // 1. Change to VARCHAR first to allow arbitrary strings
        DB::statement("ALTER TABLE aspirasi MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'submitted'");

        // 2. Map old string values to new values
        DB::table('aspirasi')->where('status', 'terkirim')->update(['status' => 'submitted']);
        DB::table('aspirasi')->where('status', 'ditanggapi')->update(['status' => 'in_progress']);
        DB::table('aspirasi')->where('status', 'ditutup')->update(['status' => 'completed']);
        
        // Handle defaults
        DB::table('aspirasi')->whereNotIn('status', ['submitted', 'approved', 'rejected', 'in_progress', 'completed'])
            ->update(['status' => 'submitted']);

        // 3. Convert back to strict ENUM with new values
        DB::statement("ALTER TABLE aspirasi MODIFY COLUMN status ENUM('submitted', 'approved', 'rejected', 'in_progress', 'completed') NOT NULL DEFAULT 'submitted'");

        // Add columns if they don't exist (using Schema check to be safe)
        if (!Schema::hasColumn('aspirasi', 'alasan_penolakan')) {
            Schema::table('aspirasi', function (Blueprint $table) {
                $table->text('alasan_penolakan')->nullable()->after('status'); // Adjusted position slightly or check existing
            });
        }
        if (!Schema::hasColumn('aspirasi', 'approved_at')) {
            Schema::table('aspirasi', function (Blueprint $table) {
                $table->timestamp('approved_at')->nullable()->after('status');
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Simpler down
        Schema::table('aspirasi', function (Blueprint $table) {
             if (Schema::hasColumn('aspirasi', 'alasan_penolakan')) $table->dropColumn(['alasan_penolakan', 'approved_at', 'rejected_at']);
        });
        // Note: Reverting ENUM is hard, leaving it as is.
    }
};