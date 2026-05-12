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
        Schema::table('quiz_attempts', function (Blueprint $table) {
            if (! Schema::hasColumn('quiz_attempts', 'answers_json')) {
                $table->json('answers_json')->nullable()->after('total_questions');
            }
            if (! Schema::hasColumn('quiz_attempts', 'time_taken')) {
                $table->integer('time_taken')->nullable()->after('answers_json');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn(['answers_json', 'time_taken']);
        });
    }
};
