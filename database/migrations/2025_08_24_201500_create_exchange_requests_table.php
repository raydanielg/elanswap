<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exchange_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->foreignId('requester_application_id')->nullable()->constrained('applications')->nullOnDelete();
            $table->string('status')->default('pending'); // pending, accepted, rejected, cancelled
            $table->text('message')->nullable();
            $table->timestamps();
            $table->unique(['requester_id','application_id'], 'uniq_request_per_app');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_requests');
    }
};
