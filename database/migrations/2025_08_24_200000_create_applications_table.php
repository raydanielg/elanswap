<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // From (current) details - snapshot from user's profile
            $table->foreignId('from_region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('from_district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('from_station_id')->nullable()->constrained('stations')->nullOnDelete();
            // To (requested) destination
            $table->foreignId('to_region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('to_district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
