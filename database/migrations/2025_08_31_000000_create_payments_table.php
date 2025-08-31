<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('method')->nullable(); // mpesa, tigopesa, airtel, card, etc
            $table->string('provider_reference')->nullable();
            $table->unsignedInteger('amount')->default(0); // amount in TZS (or smallest unit)
            $table->string('currency', 10)->default('TZS');
            $table->enum('status', ['pending','paid','failed','cancelled'])->default('pending');
            $table->json('meta')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['user_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
