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
        Schema::create('training_applications', function (Blueprint $table) {
            $table->increments('training_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('training_program_id')->constrained('training_programs')->onDelete('cascade');
            $table->enum('application_status', ['Pending', 'Approved', 'Denied'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_applications');
    }
};
