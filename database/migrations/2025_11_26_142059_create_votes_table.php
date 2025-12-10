<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('voting_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('option_id')->constrained('voting_options')->onDelete('cascade');
            $table->datetime('cast_at')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->string('signature_hash', 255)->nullable();
            
            $table->unique(['session_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
};