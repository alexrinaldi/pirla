<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique();
            $table->enum('source', ['direct', 'ota', 'phone', 'email'])->default('direct');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('adults');
            $table->integer('children')->default(0);
            $table->enum('status', ['inquiry', 'option', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'no_show'])->default('inquiry');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
