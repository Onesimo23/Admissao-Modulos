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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_subject_id')->constrained()->onDelete('cascade');
            $table->decimal('grade', 3, 1)->nullable();
            $table->integer('absent')->default(0);
            $table->timestamps();
            
            // Garante que não haverá notas duplicadas para o mesmo candidato e disciplina
            $table->unique(['candidate_id', 'exam_subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
