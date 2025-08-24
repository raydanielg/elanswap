<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->string('name');
            $table->unique(['region_id', 'name']);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
        });

        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('districts')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->unique(['district_id', 'name']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->after('phone_verified_at')->constrained('regions')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->after('region_id')->constrained('districts')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->after('district_id')->constrained('categories')->nullOnDelete();
            $table->foreignId('station_id')->nullable()->after('category_id')->constrained('stations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('station_id');
            $table->dropConstrainedForeignId('category_id');
            $table->dropConstrainedForeignId('district_id');
            $table->dropConstrainedForeignId('region_id');
        });
        Schema::dropIfExists('stations');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('regions');
    }
};
