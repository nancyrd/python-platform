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
        Schema::create('assessments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('stage_id')->constrained()->cascadeOnDelete();
        $table->string('type'); // pre | post
        $table->string('title')->nullable();
        $table->json('questions')->nullable(); // array of MCQs for MVP
        $table->timestamps();
        $table->unique(['stage_id','type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
