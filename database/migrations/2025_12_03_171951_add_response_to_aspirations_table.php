<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // FIX: Corrected table name from 'aspirations' to 'aspirasi'
        // FIX: Added idempotency check
        if (Schema::hasTable('aspirasi')) {
            Schema::table('aspirasi', function (Blueprint $table) {
                if (!Schema::hasColumn('aspirasi', 'admin_response')) {
                    $table->text('admin_response')->nullable();
                }
                if (!Schema::hasColumn('aspirasi', 'is_responded')) {
                    $table->boolean('is_responded')->default(false);
                }
                if (!Schema::hasColumn('aspirasi', 'responded_at')) {
                    $table->timestamp('responded_at')->nullable();
                }
            });
        }
    }
    
    public function down()
    {
        Schema::table('aspirasi', function (Blueprint $table) {
             $table->dropColumn(['admin_response', 'is_responded', 'responded_at']);
        });
    }
};
