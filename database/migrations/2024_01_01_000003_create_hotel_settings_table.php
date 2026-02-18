<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('currency')->default('USD');
            $table->string('timezone')->default('UTC');
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->time('checkin_time')->default('15:00');
            $table->time('checkout_time')->default('11:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_settings');
    }
};
