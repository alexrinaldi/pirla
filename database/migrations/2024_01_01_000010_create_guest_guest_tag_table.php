<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_guest_tag', function (Blueprint $table) {
            $table->foreignId('guest_id')->constrained()->onDelete('cascade');
            $table->foreignId('guest_tag_id')->constrained()->onDelete('cascade');
            
            $table->primary(['guest_id', 'guest_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_guest_tag');
    }
};
