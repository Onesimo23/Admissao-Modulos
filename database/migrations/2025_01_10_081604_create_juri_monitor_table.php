<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('juri_monitor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('juri_id')->constrained('juris')->onDelete('cascade');
            $table->foreignId('monitor_id')->constrained('monitors')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juri_monitor');
    }
};
