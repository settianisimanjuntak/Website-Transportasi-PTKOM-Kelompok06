<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('schedule_id')->constrained();
            $table->string('contact_name');
            $table->string('contact_phone', 20);
            $table->string('contact_email');
            $table->boolean('is_self_passenger')->default(true);
            $table->boolean('protection')->default(false);
            $table->unsignedInteger('protection_fee')->default(0);
            $table->unsignedInteger('insurance_fee')->default(0);
            $table->unsignedInteger('service_fee')->default(0);
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('total');
            $table->enum('payment_method', ['qris', 'va', 'ewallet'])->nullable();
            $table->enum('status', ['pending', 'paid', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index('status');
        });

        Schema::create('booking_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->string('seat_no', 5)->nullable();
            $table->string('full_name');
            $table->string('nik', 16);
            $table->enum('status', ['active', 'released'])->default('active');
            $table->timestamps();
            $table->unique(['schedule_id', 'seat_no']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('method', ['qris', 'va', 'ewallet']);
            $table->unsignedInteger('amount');
            $table->enum('status', ['waiting', 'paid', 'failed', 'expired'])->default('waiting');
            $table->string('provider')->default('mock');
            $table->string('reference')->unique();
            $table->string('bank', 20)->nullable();
            $table->text('qr_payload')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_passenger_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('code', 20)->unique();
            $table->text('qr_payload');
            $table->enum('status', ['issued', 'used', 'cancelled'])->default('issued');
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('booking_passengers');
        Schema::dropIfExists('bookings');
    }
};
