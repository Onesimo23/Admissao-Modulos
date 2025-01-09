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
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');  
            $table->string('disciplina1'); // Primeira disciplina
            $table->string('disciplina2'); // Segunda disciplina
            $table->integer('peso1')->default(1); // Peso da primeira disciplina
            $table->integer('peso2')->default(1);       
            $table->timestamps();
        });   
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplinas');
    }
};
