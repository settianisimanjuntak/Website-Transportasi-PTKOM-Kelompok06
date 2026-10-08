<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_city_id')->constrained('cities');
            $table->foreignId('destination_city_id')->constrained('cities');
            $table->unsignedSmallInteger('duration_minutes');
            $table->unsignedSmallInteger('distance_km')->nullable();
            $table->timestamps();
            $table->unique(['origin_city_id', 'destination_city_id']);
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained();
            $table->foreignId('bus_id')->constrained();
            $table->foreignId('operator_id')->constrained();
            $table->foreignId('departure_terminal_id')->constrained('terminals');
            $table->foreignId('arrival_terminal_id')->constrained('terminals');
            $table->date('service_date');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->unsignedTinyInteger('arrival_day_offset')->default(0);
            $table->unsignedInteger('price');
            $table->enum('status', ['scheduled', 'boarding', 'departed', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['service_date', 'status']);
        });

        Schema::create('schedule_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('sequence');
            $table->string('name');
            $table->time('stop_time')->nullable();
            $table->string('note')->nullable();
            $table->boolean('is_terminal')->default(false);
            $table->timestamps();
            $table->unique(['schedule_id', 'sequence']);
        });

        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->cascadeOnDelete();
            $table->string('seat_no', 5);
            $table->unsignedSmallInteger('seat_row');
            $table->string('seat_col', 3);
            $table->boolean('is_window')->default(false);
            $table->timestamps();
            $table->unique(['bus_id', 'seat_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
        Schema::dropIfExists('schedule_stops');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('routes');
    }
};
