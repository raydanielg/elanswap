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
            $table->string('provider')->nullable(); // mpesa, airtel, tigo, card
            $table->string('method')->nullable();   // e.g., stk_push, ussd, card
            $table->string('reference')->nullable(); // provider reference
            $table->string('currency', 10)->default('TZS');
            $table->unsignedBigInteger('amount'); // store in minor units (e.g., cents)
            $table->enum('status', ['pending','success','failed','cancelled'])->default('pending');
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
