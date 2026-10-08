<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('province')->nullable();
            $table->timestamps();
        });

        Schema::create('terminals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        Schema::create('bus_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('seat_layout', 10)->default('2-2');
            $table->string('badge', 10)->default('green');
            $table->timestamps();
        });

        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained();
            $table->foreignId('bus_class_id')->constrained();
            $table->string('code', 20)->unique();
            $table->string('model')->nullable();
            $table->unsignedSmallInteger('capacity')->default(30);
            $table->string('layout', 10)->default('2-2');
            $table->string('photo')->nullable();
            $table->enum('status', ['operational', 'maintenance', 'retired'])->default('operational');
            $table->timestamps();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('icon', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('bus_facility', function (Blueprint $table) {
            $table->foreignId('bus_id')->constrained()->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->primary(['bus_id', 'facility_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_facility');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('buses');
        Schema::dropIfExists('bus_classes');
        Schema::dropIfExists('operators');
        Schema::dropIfExists('terminals');
        Schema::dropIfExists('cities');
    }
};
