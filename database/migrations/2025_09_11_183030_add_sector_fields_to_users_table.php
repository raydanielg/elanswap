<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('qualification_level')->nullable()->after('station_id'); // degree|diploma
            $table->string('edu_subject_one')->nullable()->after('qualification_level');
            $table->string('edu_subject_two')->nullable()->after('edu_subject_one');
            $table->string('health_department')->nullable()->after('edu_subject_two');
            $table->index('qualification_level');
            $table->index('edu_subject_one');
            $table->index('health_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['qualification_level']);
            $table->dropIndex(['edu_subject_one']);
            $table->dropIndex(['health_department']);
            $table->dropColumn(['qualification_level','edu_subject_one','edu_subject_two','health_department']);
        });
    }
};
