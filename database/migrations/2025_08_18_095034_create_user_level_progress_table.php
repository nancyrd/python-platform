<?php
// database/migrations/2025_01_01_000000_create_user_level_progress_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_level_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('stage_id');
            $table->unsignedBigInteger('level_id');

            $table->unsignedInteger('best_score')->default(0);   // 0–100
            $table->unsignedTinyInteger('stars')->default(0);    // 0–3
            $table->unsignedInteger('attempts_count')->default(0);

            $table->boolean('passed')->default(false);
            $table->timestamp('first_passed_at')->nullable();
            $table->timestamp('last_attempt_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id','level_id']); // one row per (user,level)

            // (Optional) FKs if you want:
            // $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            // $table->foreign('stage_id')->references('id')->on('stages')->cascadeOnDelete();
            // $table->foreign('level_id')->references('id')->on('levels')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_level_progress');
    }
};
