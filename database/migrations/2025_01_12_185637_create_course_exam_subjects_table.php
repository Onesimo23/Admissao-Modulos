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
        Schema::create('course_exam_subjects', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_subject_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Garante que não haverá duplicatas de disciplina para o mesmo curso
            $table->unique(['course_id', 'exam_subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_exam_subjects');
    }
};
