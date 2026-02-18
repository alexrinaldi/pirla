<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_guest', function (Blueprint $table) {
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('guest_id')->constrained()->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            
            $table->primary(['reservation_id', 'guest_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_guest');
    }
};
