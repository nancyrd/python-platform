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
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('stage_id')->constrained()->cascadeOnDelete();
        $table->foreignId('level_id')->nullable()->constrained()->nullOnDelete();
        $table->string('kind'); // pre | level | post
        $table->unsignedInteger('score')->default(0); // 0..100
        $table->boolean('passed')->default(false);
        $table->json('answers')->nullable();
        $table->timestamp('started_at')->nullable();
        $table->timestamp('finished_at')->nullable();
        $table->timestamps();
        $table->index(['user_id','stage_id','level_id','kind']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
