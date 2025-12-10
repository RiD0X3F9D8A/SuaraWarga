<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('voting_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->datetime('mulai');
            $table->datetime('selesai');
            $table->boolean('is_public')->default(true);
            $table->boolean('allow_multiple_choice')->default(false);
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voting_sessions');
    }
};