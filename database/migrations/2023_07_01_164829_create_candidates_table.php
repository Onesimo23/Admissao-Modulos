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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id()->startingValue(10015);
            $table->uuid();
            $table->foreignId('province_id')->constrained();
            //$table->foreignId('province_district_id')->nullable()->constrained();
            $table->foreignId('special_need_id')->constrained();
            //$table->foreignId('pre_university_school_id')->nullable()->constrained();
			//$table->foreignId('pre_university_province_id')->nullable()->constrained();
            $table->foreignId('university_id')->constrained();
            $table->foreignId('regime_id')->constrained();;			
            $table->foreignId('course_id')->constrained();
			$table->foreignId('user_id')->nullable()->constrained();
            $table->string('surname');
            $table->string('name');
            $table->date('birthdate');
            $table->string('nationality');
            $table->string('gender');
            $table->string('marital_status');
            $table->string('document_type');
            $table->string('document_number');
            $table->string('nuit')->nullable();
            $table->string('cell1');
            $table->string('cell2')->nullable();
            $table->string('email')->nullable();
            $table->string('pre_university_type');
            $table->string('pre_university_year');
            $table->string('local_exam')->nullable();
			$table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
