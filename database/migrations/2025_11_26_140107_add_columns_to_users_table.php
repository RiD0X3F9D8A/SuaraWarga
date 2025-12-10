<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->after('id');
            $table->string('alamat', 255)->nullable()->after('name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->enum('role', ['admin', 'warga'])->default('warga')->after('password');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'alamat', 'phone', 'role', 'is_active']);
        });
    }
};