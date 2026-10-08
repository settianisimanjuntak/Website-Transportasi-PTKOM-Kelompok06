<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('nik', 16)->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('nik');
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
            $table->enum('role', ['user', 'admin'])->default('user')->after('password');
            $table->unsignedInteger('points')->default(0)->after('role');
            $table->string('avatar')->nullable()->after('points');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'nik', 'birth_date', 'gender', 'role', 'points', 'avatar',
            ]);
        });
    }
};
