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
    Schema::create('juri_has_candidates', function (Blueprint $table) {
        $table->foreignId('juri_id')->constrained()->onDelete('cascade');
        $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
        $table->primary(['juri_id', 'candidate_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juri_has_courses');
    }
};
