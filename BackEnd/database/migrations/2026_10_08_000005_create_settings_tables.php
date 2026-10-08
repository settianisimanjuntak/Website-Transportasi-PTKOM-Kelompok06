<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('reschedule_text')->nullable();
            $table->unsignedTinyInteger('reschedule_fee_percent')->default(10);
            $table->text('cancel_text')->nullable();
            $table->unsignedTinyInteger('refund_percent')->default(75);
            $table->unsignedSmallInteger('refund_min_hours')->default(24);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('policies');
    }
};
