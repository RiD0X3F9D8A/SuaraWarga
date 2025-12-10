<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('voting_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('voting_sessions')->onDelete('cascade');
            $table->string('pilihan_label', 200);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voting_options');
    }
};