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
        Schema::create('enrollees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('training_programs')->onDelete('cascade');
            $table->foreignId('training_application_id')->constrained('training_applications')->onDelete('cascade');            
            $table->enum('completion_status', ['Completed', 'Ongoing', 'Not completed'])->default('Ongoing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollees');
    }
};
