<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            // Place it where you like; after('title') is just a suggestion
            $table->text('instructions')->nullable()->after('title');

            // If you don’t already have a JSON/longText column for content, ensure it exists:
            // Use JSON if MySQL >= 5.7 / MariaDB >= 10.2.7; otherwise use longText
            // $table->json('content')->nullable()->change(); // if it already exists but wrong type
            // or:
            // $table->longText('content')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropColumn('instructions');
        });
    }
};

