<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            // add after `title` just for nicer order (position isn’t required)
            if (!Schema::hasColumn('assessments', 'time_limit')) {
                $table->unsignedInteger('time_limit')->nullable()->after('title');
            }
            if (!Schema::hasColumn('assessments', 'instructions')) {
                $table->text('instructions')->nullable()->after('time_limit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            if (Schema::hasColumn('assessments', 'instructions')) {
                $table->dropColumn('instructions');
            }
            if (Schema::hasColumn('assessments', 'time_limit')) {
                $table->dropColumn('time_limit');
            }
        });
    }
};
