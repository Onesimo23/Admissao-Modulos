<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('juri_has_monitors', function (Blueprint $table) {
            $table->foreignId('juri_id')->constrained()->onDelete('cascade');
            $table->foreignId('monitor_id')->constrained()->onDelete('cascade');
            $table->primary(['juri_id', 'monitor_id']);
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juri_has_monitors');
    }
};
