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
        Schema::create('crowdfund_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('training_programs')->onDelete('cascade');
            $table->float('goal')->nullable();
            $table->float('raised_amount')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crowdfund_events');
    }
};
