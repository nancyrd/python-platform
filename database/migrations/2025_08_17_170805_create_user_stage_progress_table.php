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
        Schema::create('user_stage_progress', function (Blueprint $table) {
            $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('stage_id')->constrained()->cascadeOnDelete();
        $table->timestamp('pre_completed_at')->nullable();
        $table->timestamp('post_completed_at')->nullable();
        $table->unsignedInteger('unlocked_to_level')->default(0); // 0 until pre-assessment
        $table->json('stars_per_level')->nullable(); // {"1":3,"2":1}
        $table->timestamp('last_activity_at')->nullable();
        $table->timestamps();
        $table->unique(['user_id','stage_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stage_progress');
    }
};
