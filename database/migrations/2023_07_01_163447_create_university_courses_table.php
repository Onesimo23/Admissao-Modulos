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
        Schema::create('university_courses', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('university_id')->constrained();
			$table->foreignId('course_id')->constrained();
			$table->foreignId('regime_id')->constrained(); // $table->string('regime');	
            $table->string('edital_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_courses');
    }
};
