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
        Schema::create('levels', function (Blueprint $table) {
                $table->id();
        $table->foreignId('stage_id')->constrained()->cascadeOnDelete();
        $table->unsignedInteger('index')->default(1); // 1..N within a stage
        $table->string('type'); // drag_drop | fill_blank | match_output | etc.
        $table->string('title');
        $table->unsignedInteger('pass_score')->default(80); // percent to pass
        $table->json('content')->nullable(); // payload for the level
        $table->timestamps();
        $table->unique(['stage_id','index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
