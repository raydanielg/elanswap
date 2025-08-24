<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedBigInteger('paired_application_id')->nullable()->after('status');
            $table->foreign('paired_application_id')->references('id')->on('applications')->nullOnDelete();
            $table->index(['from_region_id','to_region_id','status']);
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['paired_application_id']);
            $table->dropColumn('paired_application_id');
            $table->dropIndex(['from_region_id','to_region_id','status']);
        });
    }
};
